<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\ProductController;

// 商品一覧
Route::get('/products', [ProductController::class, 'index']);

// 商品詳細
Route::get('/products/detail', [ProductController::class, 'show']);


use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
// ログイン画面
Route::get('/login', [UserController::class, 'login_Get']);
Route::post('/login', [UserController::class, 'login_Post']);

// 新規登録画面
Route::get('/account', [UserController::class, 'account_Get']);
Route::post('/account', [UserController::class, 'account_Post']);