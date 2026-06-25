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
        //未ログインならメッセージ付きでリダイレクト
        if (!Auth::check()) {
            return redirect('/login')->with('error_message', 'カートを見るにはログインが必要です。');
        }

        // カートアイテムを取得して、商品情報をマージした$productsを作る
        $cartItems = Cart_item::where('user_id', Auth::id())->get();

        $products = $cartItems->map(function ($item) {
            $product = Product::find($item->product_id);

            // 他のユーザーの購入などで、カート内数量が現在の在庫数を超過している場合、最大在庫数に自動補正
            if ($item->quantity > $product->stock) {
                $decreasedMessages[] = $product->name . 'の在庫上限（' . $product->stock . '個）に達しました。';
                $item->quantity = $product->stock;
                $item->save(); // データベース側も更新
            }

            $product->quantity = $item->quantity; // カートの個数をproductに付与
            $product->cart_item_id = $item->id;   // 削除用にカートIDも付与
            $image = Product_image::find($product->image_id);
            $product->image = $image ? $image->image_url : null;
            return $product;
        });
        if (!empty($decreasedMessages)) {
            $existingErrors = session()->get('errors_array', []);
            session()->flash('errors_array', array_merge($existingErrors, $decreasedMessages));
        }

        return view('cart.cart', compact('products'));
    }

    // 指定した商品をカートに追加
    public function addCart(Request $request)
    {
        //未ログインならメッセージ付きでリダイレクト
        if (!Auth::check()) {
            return redirect('/login')->with('error_message', 'カートに商品を入れるにはログインが必要です。');
        }
        $product = Product::find($request->input('product_id'));

        // 詳細画面のドロップダウンで選択された数量を取得
        $inputQuantity = (int)$request->input('quantity', 1);

        // カートの現在の個数を取得
        $cartItem = Cart_item::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();
        $currentQuantity = $cartItem ? $cartItem->quantity : 0;

        // 在庫チェック
        if (($currentQuantity + $inputQuantity) > $product->stock) {
            return redirect('/cart')->with('error', $product->name . 'の在庫が足りません。あと ' . ($product->stock - $currentQuantity) . ' 個追加可能です。');
        }

        // 数量を追加
        Cart_item::updateOrCreate(
            ['user_id' => Auth::id(), 'product_id' => $product->id],
            ['quantity' => DB::raw("quantity + {$inputQuantity}")]
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

    // 個数を減らす（ーボタン）
    public function decreaseCart(Request $request)
    {
        $product = Product::find($request->input('product_id'));
        $cartItem = Cart_item::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->firstOrFail();

        if ($cartItem->quantity > 0) {
            $cartItem->quantity -= 1;
        }

        // 変更後の数値が、商品テーブルの在庫数を超過した場合は最大在庫数を適用
        if ($cartItem->quantity > $product->stock) {
            $cartItem->quantity = $product->stock;
            session()->flash('error', $product->name . 'は他のお客様の購入により在庫が減少したため、最大在庫数に調整されました。');
        }

        $cartItem->save();
        return redirect('/cart');
    }

    // 個数を増やす（＋ボタン）
    public function increaseCart(Request $request)
    {
        $product = Product::find($request->input('product_id'));
        $cartItem = Cart_item::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->firstOrFail();

        $cartItem->quantity += 1;

        // 変更後の数値が、商品テーブルの在庫数を超過した場合は最大在庫数を適用
        if ($cartItem->quantity > $product->stock) {
            $cartItem->quantity = $product->stock;
            session()->flash('error', $product->name . 'の在庫上限（' . $product->stock . '個）に達しました。');
        }

        $cartItem->save();
        return redirect('/cart');
    }
}
