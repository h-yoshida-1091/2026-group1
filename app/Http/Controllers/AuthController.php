<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * GET /login
     * ログイン画面を表示
     */
    public function showLogin()
    {
        // resources/views/login.blade.php を表示
        return view('accounts.login');
    }

    /**
     * POST /login
     * ログイン処理（仮置き）
     */
    public function login(Request $request)
    {
        // 入力値の取得
        $email = $request->input('email');
        $password = $request->input('password');

        // 仮のバリデーション・認証チェック
        if ($email === 'test@example.com' && $password === 'password') {
            // 本来はAuth::login()等を使いますが、今回は仮でセッションに入れます
            session(['user_name' => '山田 太郎（テストユーザー）']);
            
            return redirect('/products');
        }

        // 失敗したらメッセージを持ってログイン画面にリダイレクト
        return redirect('/login')->with('error_message', 'メールアドレスまたはパスワードが違います（※テスト用：test@example.com / password で入れます）');
    }

    /**
     * GET /account
     * 新規登録画面を表示
     */
    public function showRegister()
    {
        // resources/views/user_signup.blade.php を表示
        return view('accounts.user_signup');
    }

    /**
     * POST /account
     * 新規登録処理（仮置き）
     */
    public function register(Request $request)
    {
        // フォームからの入力をすべて取得
        $data = $request->only(['name', 'email', 'password', 'address']);

        // 仮置き：入力されたデータを画面に表示して確認する
        return view('user_signup', [
            'registered_data' => $data
        ]);
    }
}