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