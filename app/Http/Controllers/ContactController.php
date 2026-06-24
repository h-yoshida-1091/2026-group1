<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ContactController extends Controller
{
    // お問い合わせ画面の表示
    public function index()
    {
        // 未ログインならログイン画面へ遷移させ、メッセージを表示
        if (!Auth::check()) {
            return redirect('/login')->with('error_message', 'お問い合わせ機能を利用するにはログインが必要です。');
        }

        $categories = Category::all();

        // ログイン中のユーザー情報を取得
        $user = Auth::user();

        return view('contact.index', compact('categories', 'user'));
    }

    // DBへの保存処理
    public function store(Request $request)
    {
        //  念のためPOST時も未ログインチェック
        if (!Auth::check()) {
            return redirect('/login')->with('error_message', 'お問い合わせ機能を利用するにはログインが必要です。');
        }

        $userId = Auth::id();
        $userEmail = Auth::user()->email;

        // スパム対策：過去24時間以内に同じユーザー（または同じメールアドレス）からの送信があるかチェック
        $lastContact = Contact::where('email', $userEmail)
            ->where('created_at', '>=', Carbon::now()->subDay())
            ->first();

        if ($lastContact) {
            return redirect('/contact')->with('error_message', '連続でのお問い合わせは制限されています。次のお問い合わせは、前回送信から24時間後にお願いいたします。');
        }

        // 入力制限しているため、名前とメールアドレスはセッションから強制的に取得してバリデーションにかける
        $request->merge([
            'name'  => Auth::user()->name,
            'email' => $userEmail,
        ]);

        // バリデーション
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ], [
            'subject.required' => '件名を入力してください。',
            'message.required' => 'お問い合わせ内容を入力してください。',
        ]);

        // DBへ保存
        Contact::create($validated);

        //  完了メッセージに注記を追加してリダイレクト
        return redirect('/contact')->with('success_message', "お問い合わせを受け付けました。ありがとうございました。\nお問い合わせに対する回答は、後日ご登録のメールアドレスに送信いたします。");
    }
}
