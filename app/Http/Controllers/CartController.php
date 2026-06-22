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
        // カートアイテムを取得して、商品情報をマージした$productsを作る
        $cartItems = Cart_item::where('user_id', Auth::id())->get();

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
        $product = Product::find($request->input('product_id'));

        // カートの現在の個数を取得
        $cartItem = Cart_item::where('user_id', Auth::id())
                            ->where('product_id', $product->id)
                            ->first();
        $currentQuantity = $cartItem ? $cartItem->quantity : 0;

        // 在庫チェック
        if ($currentQuantity >= $product->stock) {
            return redirect('/cart')->with('error', $product->name . 'の在庫が足りません');
        }

        Cart_item::updateOrCreate(
            ['user_id' => Auth::id(), 'product_id' => $product->id],
            ['quantity' => DB::raw("quantity + 1")]
        );
        return redirect('/cart');
    }

    // 指定した商品をカートから削除
    public function delete(Request $request)
    {
        Cart_item::where('user_id', Auth::id())
                ->where('product_id', $request->input('id'))
                ->firstOrFail()
                ->delete();

        return redirect('/cart');
    }

    // 個数を減らす
    public function decreaseCart(Request $request)
    {
        $cartItem = Cart_item::where('user_id', Auth::id())
                            ->where('product_id', $request->input('product_id'))
                            ->firstOrFail();

        // 負の個数にならないように
        if ($cartItem->quantity > 0) {
            $cartItem->quantity -= 1;
            $cartItem->save();
        }

        return redirect('/cart');
    }

    // 個数を増やす
    public function increaseCart(Request $request)
    {
        $product = Product::find($request->input('product_id'));

        // カートの現在の個数を取得
        $cartItem = Cart_item::where('user_id', Auth::id())
                            ->where('product_id', $product->id)
                            ->firstOrFail();

        // 在庫チェック
        if ($cartItem->quantity >= $product->stock) {
            return redirect('/cart')->with('error', $product->name . 'の在庫が足りません');
        }

        $cartItem->quantity += 1;
        $cartItem->save();
        $id = $request->id;

        // カートから削除
        Cart_item::where('product_id', $id)->delete();

        return redirect('/cart');
    }
}