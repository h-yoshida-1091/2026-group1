<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //=====================================
    //　　ログイン機能
    //=====================================

    //ログイン画面の表示
    public function login_Get()
    {
        return view('accounts.login');
    }

    //ログイン処理
    public function login_Post(Request $request)
    {
        //入力チェック
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        //データベースの情報と照合
        if(Auth::attempt($credentials)){
            //セッションの再生成（セキュリティ対策）
            $request->session()->regenerate();
            //本来行こうとしていたページへリダイレクト
            return redirect()->intended('/products');
        }

        //失敗したらメッセージを持ってログイン画面にリダイレクト
        return redirect('/login')->with('error_message', 'メールアドレスまたはパスワードが違います');
    }


    //====================================
    //　　会員登録機能
    //====================================

    //会員登録画面の表示
    public function account_Get()
    {
        return view('accounts.user_signup');
    }

    //会員登録処理
    public function account_Post(Request $request)
    {
        //入力チェック
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|max:255',
            'address' => 'required|string|max:255',
        ]);

        //データベースへ格納
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), //暗号化
            'address' => $request->address
        ]);

        //会員登録後、ログイン画面に移動しメッセージを表示
        return redirect('/login')->with('error_message', '会員登録が完了しました。ログインしてください。');
    }
}
