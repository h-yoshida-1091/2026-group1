<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product; // 商品テーブルのチェックに必要

class AdminCategoryController extends Controller
{
    // カテゴリー一覧（追加・変更・削除のベース画面）
    public function index()
    {
        // 確実に対象商品の存在を数えてフラグ（true/false）を立てる方法
        $categories = Category::all()->map(function($category) {
            $category->products_exists = Product::where('category_id', $category->id)->exists();
            return $category;
        });

        return view('admin.admin_category', compact('categories'));
    }

    // カテゴリー追加処理
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
        ], [
            'name.required' => 'カテゴリー名を入力してください。',
            'name.max'      => 'カテゴリー名は100文字以内で入力してください。',
        ]);

        Category::create([
            'name' => $request->input('name')
        ]);

        return redirect('/admin/categories')->with('success', 'カテゴリーを追加しました。');
    }

    // カテゴリー変更（更新）処理
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
        ], [
            'name.required' => 'カテゴリー名を入力してください。',
            'name.max'      => 'カテゴリー名は100文字以内で入力してください。',
        ]);

        $category->update([
            'name' => $request->input('name')
        ]);

        return redirect('/admin/categories')->with('success', 'カテゴリーを更新しました。');
    }

    // カテゴリー削除処理
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:categories,id'
        ]);

        $categoryId = $request->input('id');

        // 安全弁：サーバー側でも商品テーブル（products）で使用中か最終チェック
        $isUsed = Product::where('category_id', $categoryId)->exists();

        if ($isUsed) {
            return redirect('/admin/categories')->with('error', 'このカテゴリーは商品に登録されているため削除できません。');
        }

        // 使われていない場合のみ削除を実行
        Category::findOrFail($categoryId)->delete();

        return redirect('/admin/categories')->with('success', 'カテゴリーを削除しました。');
    }
}