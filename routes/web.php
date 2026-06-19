<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;

// 商品一覧
Route::get('/products', [ProductController::class, 'index']);

// 商品詳細
Route::get('/products/detail', [ProductController::class, 'show']);

// 購入確認
Route::get('/purchase', [OrderController::class, 'order']);

//　購入完了
Route::get('/purchase/complete', [OrderController::class, 'complete']);