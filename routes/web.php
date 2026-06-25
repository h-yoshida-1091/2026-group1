<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminCategoryController;
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

// カテゴリー管理画面（一覧）
Route::get('/admin/categories', [AdminCategoryController::class, 'index']);
// カテゴリー追加
Route::post('/admin/categories', [AdminCategoryController::class, 'store']);
// カテゴリー変更
Route::post('/admin/categories/edit/{id}', [AdminCategoryController::class, 'update']);
// カテゴリー削除
Route::post('/admin/categories/delete', [AdminCategoryController::class, 'destroy']);

// お問い合わせ画面の表示
Route::get('/contact', [ContactController::class, 'index']);
// お問い合わせデータの保存処理
Route::post('/contact', [ContactController::class, 'store']);
// 管理者用お問い合わせ一覧画面
Route::get('/admin/contact', [ContactController::class, 'adminIndex']);
// 管理者用返信画面の表示
Route::get('/admin/contact/{id}/reply', [ContactController::class, 'adminReply']);
// 管理者用返信処理（ステータス更新）
Route::post('/admin/contact/{id}/reply', [ContactController::class, 'adminSendReply']);
// お問い合わせをゴミ箱に移動する処理
Route::post('/admin/contact/{id}/trash', [ContactController::class, 'adminTrash']);
// ゴミ箱に入ったお問い合わせの一覧画面
Route::get('/admin/trash', [ContactController::class, 'adminTrashIndex']);
// ゴミ箱から元に戻す（復元）処理
Route::post('/admin/contact/{id}/restore', [ContactController::class, 'adminRestore']);
// ゴミ箱から完全に削除する（個別物理削除）処理
Route::delete('/admin/contact/{id}/force-delete', [ContactController::class, 'adminForceDelete']);
// ゴミ箱から選択したデータを一括で完全に削除する処理
Route::delete('/admin/contact/bulk-delete', [ContactController::class, 'adminBulkDelete']);

// レビュー投稿・削除用ルート
Route::post('/reviews', [ReviewController::class, 'store'])->middleware('auth');
Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->middleware('auth');


