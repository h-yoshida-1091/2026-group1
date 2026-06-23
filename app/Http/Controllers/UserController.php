<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
            'password' => 'required|string|min:8|max:255|confirmed',
            'address' => 'required|string|max:255',
        ],[
            //エラー表示
            'email.unique' => 'このメールアドレスは既に登録されています。',
            'password.confirmed' => 'パスワードが一致していません。',
            'password.min' => 'パスワードは８文字以上で入力してください。',
        ]);

        //データベースへ格納
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), //暗号化
            'address' => $request->address
        ]);

        //会員登録後、ログイン画面に移動しメッセージを表示
        return redirect('/login')->with('success_message', '会員登録が完了しました。ログインしてください。');
    }


    //===========================================
    //      ログアウト機能
    //==========================================
    public function logout(Request $request)
    {
        //ログインを解除
        Auth::logout();

        //現在のセッションを無効化
        $request->session()->invalidate();

        //CSRFトークンを再生成（二重送信やセッション固定攻撃の対策）
        $request->session()->regenerateToken();

        //ログアウト後はログイン画面にメッセージを表示
        return redirect('/login')->with('success_message', 'ログアウトしました。');
    }


    //===========================================
    //      アカウント編集機能
    //===========================================
    public function edit_Get()
    {
        $user = Auth::user();

        return view('accounts.account_edit', compact('user'));
    }

    public function edit_Post(Request $request)
    {
        //ログイン中のユーザーを取得
        $user = User::findOrFile(Auth::id());

        //バリデーション
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            //'password' => ['nullable', 'string', 'min:8', 'max:255', 'confirmed'],
            'address' => ['required', 'string', 'max:255'],
        ]);

        //更新
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->address = $validated['address'];

        //パスワードが入力されていた場合のみ更新
        //if (!empty($validated['password'])) {
        //    $user->password = bcrypt($validated['password']);
        //}

        //データベースに保存
        $user->save();

        //リダイレクト
        return redirect()->back()->with('status', 'profile-updated');
    }
}
