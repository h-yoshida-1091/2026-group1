<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart_item;
use App\Models\Product;
use App\Models\Product_image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{

    // カート一覧表示
    public function index()
    {
        $userId = 1; // テスト用に固定

        $cartItems = Cart_item::where('user_id', $userId)->get();

        // カートアイテムを取得して、商品情報をマージした$productsを作る
        //$cartItems = Cart_item::where('user_id', Auth::id())->get();

        $products = $cartItems->map(function ($item) {
            $product = Product::find($item->product_id);
            $product->quantity = $item->quantity; // カートの個数をproductに付与
            $product->cart_item_id = $item->id;   // 削除用にカートIDも付与
            $image = Product_image::find($product->image_id);
            $product->image = $image ? $image->image_url : null;
            return $product;
        });

        return view('cart.cart', compact('products'));
    }

    // 指定した商品をカートに追加
    public function addCart(Request $request)
    {
        $userId = 1; // テスト用に固定

        Cart_item::updateOrCreate(
            ['user_id' => $userId, 'product_id' => $request->input('product_id')],
            ['quantity' => DB::raw("quantity + {$request->input('quantity', 1)}")]
        );

        return redirect('/cart');
    }

    // 指定した商品をカートから削除
    public function delete(Request $request)
    {
        $id = $request->id;

        // カートから削除
        Cart_item::where('product_id', $id)->delete();

        return redirect('/cart');
    }
}
