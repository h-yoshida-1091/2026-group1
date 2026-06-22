<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Product_image;
use App\Models\Category;
use Illuminate\Support\Facades\Auth; // Authを使うために必須
use Illuminate\Support\Facades\DB;   // DBファサードを使うために必須

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
        $keyword = $request->input('keyword');
        $categoryId = $request->input('category_id');

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
        $userId = Auth::id() ?? 0;

        foreach ($products as $product) {
            $image = Product_image::find($product->image_id);
            $product->image_url = $image ? $image->image_url : null;

            $product->is_favorited = DB::table('favorites')
                ->where('user_id', $userId)
                ->where('product_id', $product->id)
                ->exists();
        }

        // lineupに渡す
        return view('products.lineup', compact('products', 'categories', 'keyword', 'categoryId'));
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

        // お気に入り登録の有無を判定
        $userId = Auth::id() ?? 0;
        $product->is_favorited = DB::table('favorites')
            ->where('user_id', $userId)
            ->where('product_id', $product->id)
            ->exists();

        // 詳細画面へ
        return view('products.item_detail', compact('product', 'categories'));
    }

    //お気に入り登録処理
    public function favorite(Request $request)
    {
        //セッションからログインユーザーのIDを取得

        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        //商品IDを取得
        $productId = $request->input('product_id');

        // 二重登録を防ぎつつ、お気に入りテーブルに挿入
        DB::table('favorites')->updateOrInsert([
            'user_id' => $userId,
            'product_id' => $productId,
        ], [
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json(['status' => 'success']);
    }

    //お気に入り削除処理
    public function unfavorite(Request $request)
    {
        //セッションからログインユーザーのIDを取得 
        $userId = Auth::id();

        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        //商品IDを取得
        $productId = $request->input('product_id');

        // user_id と product_id の組み合わせで削除
        DB::table('favorites')
            ->where('user_id', $userId)
            ->where('product_id', $productId)
            ->delete();

        return response()->json(['status' => 'success']);
    }
}
