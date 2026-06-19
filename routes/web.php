<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;

// 商品一覧
Route::get('/products', [ProductController::class, 'index']);

// 商品詳細
Route::get('/products/detail', [ProductController::class, 'show']);


use App\Http\Controllers\AuthController;
// ログイン画面
Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login']);

// 新規登録画面
Route::get('/account', [AuthController::class, 'showRegister']);
Route::post('/account', [AuthController::class, 'register']);

// カート一覧
Route::get('/cart', [CartController::class, 'index']);

// カートに商品を追加
Route::post('/cart/add', [CartController::class, 'addCart']);

// カートから商品を削除
Route::post('/cart/delete', [CartController::class, 'deleteCart']);