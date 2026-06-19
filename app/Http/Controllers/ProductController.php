<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Product_image;

class ProductController extends Controller
{

    // 商品一覧
    public function index()
    {
        // productsテーブルから全件取得
        $products = Product::all();

        foreach ($products as $product) {
            $image = Product_image::find($product->image_id);
            $product->image_url = $image ? $image->image_url : null;
        }

        // lineupに渡す
        return view('products.lineup', compact('products'));
    }

    // 商品詳細
    public function show(Request $request)
    {
        // 指定IDの商品を取得
        $id = $request->query('id');
        $product = Product::findOrFail($id);

        $image = Product_image::find($product->image_id);
        $product->image_url = $image ? $image->image_url : null;

        // 詳細画面へ
        return view('products.item_detail', compact('product'));
    }
}
