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
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AdminProductController;
use Symfony\Component\Finder\Iterator\VcsIgnoredFilterIterator;

// 商品一覧
Route::get('/products', [ProductController::class, 'index']);
// お気に入り登録（非同期）
Route::post('/products/favorite', [ProductController::class, 'favorite']);
// お気に入り削除（非同期）
Route::post('/products/unfavorite', [ProductController::class, 'unfavorite']);

// 商品詳細
Route::get('/products/detail', [ProductController::class, 'show']);

// 購入確認
Route::post('/purchase/confirm', [OrderController::class, 'confirm']);

// 今すぐ購入
Route::post('/purchase/now', [OrderController::class, 'nowPurchase']);

//　購入完了
Route::post('/purchase/complete', [OrderController::class, 'complete']);

//注文履歴
Route::middleware(['auth'])->group(function () {
    Route::get('/purchase/history', [UserController::class, 'showOrderHistory'])->name('purchase.history');
});

// ログイン画面
Route::get('/login', [UserController::class, 'login_Get'])->name('login');
Route::post('/login', [UserController::class, 'login_Post']);

// 新規登録画面
Route::get('/account', [UserController::class, 'account_Get']);
Route::post('/account', [UserController::class, 'account_Post']);

//ログアウト機能
Route::post('/logout', [UserController::class, 'logout']);

//アカウント編集機能
Route::get('/account/edit', [UserController::class, 'edit_Get'])->name('account.edit');
Route::put('/account/update', [UserController::class, 'edit_Post'])->name('account.update');

//アカウント削除機能
Route::delete('/account/destroy', [UserController::class, 'destroy'])->name('account.destroy');

// カート一覧
Route::get('/cart', [CartController::class, 'index']);
// カートに商品を追加
Route::post('/cart/add', [CartController::class, 'addCart']);
// カートから商品を削除
Route::post('/cart/delete', [CartController::class, 'delete']);
// 個数を減らす
Route::post('/cart/decrease', [CartController::class, 'decreaseCart']);
// 個数を増やす
Route::post('/cart/increase', [CartController::class, 'increaseCart']);

// 管理画面
Route::get('/admin/products', [AdminProductController::class, 'index']);
// 商品削除
Route::post('/admin/products/delete', [AdminProductController::class, 'destroy']);
// 商品編集画面と更新処理
Route::get('/admin/products/edit/{id}', [AdminProductController::class, 'edit']);
Route::post('/admin/products/edit/{id}', [AdminProductController::class, 'update']);
// 商品追加画面と保存処理
Route::get('/admin/products/create', [AdminProductController::class, 'create']);
Route::post('/admin/products/create', [AdminProductController::class, 'store']);
