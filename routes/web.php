<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

// 商品一覧
Route::get('/products', [ProductController::class, 'index']);
// お気に入り登録（非同期）
Route::post('/products/favorite', [ProductController::class, 'favorite']);
// お気に入り削除（非同期）
Route::post('/products/unfavorite', [ProductController::class, 'unfavorite']);

// 商品詳細
Route::get('/products/detail', [ProductController::class, 'show']);

// 購入確認
Route::get('/purchase', [OrderController::class, 'order']);

//　購入完了
Route::get('/purchase/complete', [OrderController::class, 'complete']);


// ログイン画面
Route::get('/login', [UserController::class, 'login_Get']);
Route::post('/login', [UserController::class, 'login_Post']);

// 新規登録画面

Route::get('/account', [UserController::class, 'account_Get']);
Route::post('/account', [UserController::class, 'account_Post']);

//ログアウト機能
Route::post('/logout', [UserController::class, 'logout']);

//ゲストユーザー機能
Route::post('/guest', [UserController::class, 'guestLogin']);

// Route::get('/account', [AuthController::class, 'showRegister']);
// Route::post('/account', [AuthController::class, 'register']);

// カート一覧
Route::get('/cart', [CartController::class, 'index']);

// カートに商品を追加
Route::post('/cart/add', [CartController::class, 'addCart']);

// カートから商品を削除
Route::post('/cart/delete', [CartController::class, 'deleteCart']);

