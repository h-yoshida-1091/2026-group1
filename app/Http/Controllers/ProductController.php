<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{

    // 商品一覧
    public function index()
    {
        // productsテーブルから全件取得
        $products = Product::all();

        // lineupに渡す
        return view('products.lineup', compact('products'));
    }

    // 商品詳細
    public function show(Request $request)
    {
        // 指定IDの商品を取得
        $id = $request->query('id');
        $product = Product::findOrFail($id);

        // 詳細画面へ
        return view('products.item_detail', compact('product'));
    }
}
