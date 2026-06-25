<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * レビューの投稿処理
     */
    public function store(Request $request)
    {
        // 未ログインなら弾く
        if (!Auth::check()) {
            return redirect('/login')->with('error_message', 'レビューを投稿するにはログインが必要です。');
        }

        // バリデーション（入力チェック）
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating'     => 'required|numeric|min:0|max:5',
            'comment'    => 'required|string|max:1000',
            'title'      => 'nullable|string|max:100',
        ]);

        try {
            // レビューの保存（ユニーク制約があるため、すでに投稿済みの場合はエラーになります）
            Review::create([
                'user_id'    => Auth::id(),
                'product_id' => $request->product_id,
                'rating'     => $request->rating,
                'title'      => $request->title,
                'comment'    => $request->comment,
            ]);

            return redirect()->back()->with('success', 'レビューを投稿しました。');

        } catch (\Exception $e) {
            // ユニーク制約に引っかかった場合などの対策
            return redirect()->back()->with('error', 'この商品へのレビューは既に投稿されています。');
        }
    }

    /**
     * レビューの削除処理
     */
    public function destroy($id)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        // 削除するレビューを取得
        $review = Review::findOrFail($id);

        // ★【仕様適用】自分のレビューのみ削除可能にするチェック
        if ($review->user_id !== Auth::id()) {
            return redirect()->back()->with('error', '他のユーザーのレビューは削除できません。');
        }

        $review->delete();

        return redirect()->back()->with('success', 'レビューを削除しました。');
    }
}