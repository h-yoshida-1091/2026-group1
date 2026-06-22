<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart_item;


class OrderController extends Controller
{
    public function order(Request $request)
    {
        // 1. セッションからログイン中のユーザー情報を取得
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'ログインが必要です。');
        }

        // 2. CartItemモデルを起点に、products、product_imagesテーブルを結合
        // ※「productsテーブルに image_url がある」場合は、2つ目の join は不要です
        $cartItems = CartItem::join('products', 'cart_items.product_id', '=', 'products.id')
            ->leftJoin('product_images', 'products.id', '=', 'product_images.product_id')
            ->where('cart_items.user_id', $user->id)
            ->select(
                'cart_items.quantity',
                'products.name as product_name',
                'products.price as product_price',
                'product_images.image_url' // productsにある場合は 'products.image_url' に変更
            )
            ->get();

        // 3. 合計金額を計算
        $totalPrice = 0;
        foreach ($cartItems as $item) {
            $totalPrice += $item->quantity * $item->product_price;
        }

        // 4. ビューにデータを渡す
        return view('orders.confirm', [
            'user' => $user,
            'cartItems' => $cartItems,
            'totalPrice' => $totalPrice
        ]);
    }

    public function auth()
    {
        return view('buy_completed');
    }

    public function complete()
    {
        return view('buy_completed');
    }
}
