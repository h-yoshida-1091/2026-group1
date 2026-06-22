<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Product_image;
use App\Models\Category;

class ProductController extends Controller
{

    // 商品一覧
    public function index(Request $request)
    {
        // productsテーブルから全取得
        $products = Product::all();
        //ヘッダー用にカテゴリーを全件取得
        $categories = Category::all();
        $query = Product::query();

        //商品名検索処理
        if ($request->filled('keyword')) {
        $keyword = $request->input('keyword');
        $query->where('name', 'LIKE', "%{$keyword}%");
    }
        //カテゴリ検索処理
    if ($request->filled('category_id')) {
        $categoryId = $request->input('category_id');
        $query->where('category_id', $categoryId);
    }
    $products = $query->get();

        foreach ($products as $product) {
            $image = Product_image::find($product->image_id);
            $product->image_url = $image ? $image->image_url : null;
        }

        // lineupに渡す
        return view('products.lineup', compact('products', 'categories'));
    }

    // 商品詳細
    public function show(Request $request)
    {

        //ヘッダー用にカテゴリーを全取得
        $categories = Category::all();
        // 指定IDの商品を取得
        $id = $request->query('id');
        $product = Product::findOrFail($id);

        $image = Product_image::find($product->image_id);
        $product->image_url = $image ? $image->image_url : null;

        // 詳細画面へ
        return view('products.item_detail', compact('product', 'categories'));
    }
}
