<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //====================================
    //　　会員登録機能
    //====================================

    //会員登録画面の表示
    public function user_Signup()
    {
        return view('user_signup.blade');
    }

    //会員登録処理
    public function account(Request $request)
    {
        //入力チェック
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|max:255|confirmed',
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
        return redirect()->route('login')->with('success', '会員登録が完了しました。ログインしてください。');
    }

    //=====================================
    //　　ログイン機能
    //=====================================

    //ログイン画面の表示
    public function login()
    {
        return view('login');
    }

    //ログイン処理
    public function logins(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    }

}
