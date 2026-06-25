<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    // ユーザー一覧
    public function index()
    {
        $users = User::all();
        $categories = \DB::table('categories')->get();
        return view('admin.admin_user', compact('users', 'categories'));
    }

    // ユーザー追加処理
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|min:8',
            'postal_code' => 'nullable',
            'address'     => 'required',
        ], [
            'name.required'     => '名前を入力してください',
            'email.required'    => 'メールアドレスを入力してください',
            'email.email'       => '正しいメールアドレスを入力してください',
            'email.unique'      => 'このメールアドレスはすでに使用されています',
            'password.required' => 'パスワードを入力してください',
            'password.min'      => 'パスワードは8文字以上で入力してください',
            'address.required'  => '住所を入力してください',
        ]);

        User::create([
            'name'        => $request->input('name'),
            'email'       => $request->input('email'),
            'password'    => Hash::make($request->input('password')),
            'postal_code' => $request->input('postal_code'),
            'address'     => $request->input('address'),
        ]);

        return redirect('/admin/users');
    }
}