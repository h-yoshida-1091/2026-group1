<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Cart_item;
use App\Models\Product;
use App\Models\Product_image;
use Exception;

class OrderController extends Controller
{
    public function confirm(Request $request)
    {
        // 1. セッションからログイン中のユーザー情報を取得
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'ログインが必要です。');
        }

        // 2. カートの中身を、ユーザーIDを基に、商品情報と合わせて取得
        $cartItems = Cart_Item::where('user_id', $user->id)
            ->select(
                'quantity',
                'product_id',
            )
            ->get();

        $products = Product::whereIn('id', $cartItems->pluck('product_id'))->get();

        $productImages = Product_image::whereIn('id', $products->pluck('image_id'))->get();

        // 3. 商品ごとに小計を計算
        $subtotals = [];
        foreach ($cartItems as $cartItem) {
            $product = $products->firstWhere('id', $cartItem->product_id);
            if ($product) {
                $subtotals[] = $product->price * $cartItem->quantity;
            }
        }

        // 4. 合計金額を計算
        $total = 0;
        foreach ($subtotals as $subtotal) {
            $total += $subtotal;
        }

        // 5. ビューにデータを渡す
        return view('purchase.confirm', [
            'user' => $user,
            'cartItems' => $cartItems,
            'products' => $products,
            'productImages' => $productImages,
            'subtotals' => $subtotals,
            'total' => $total,
        ]);
    }

    /**
     * 注文を確定し、データを保存する処理
     */
    public function complete(Request $request)
    {
        // 1. ログインユーザーの取得
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'ログインが必要です。');
        }

        // 2. 現在のカートの中身を、商品情報と合わせて取得
        $cartItems = Cart_item::where('user_id', $user->id)
            ->select('cart_items.*')
            ->get();

        // 3. 商品ごとに小計を計算
        $products = Product::whereIn('id', $cartItems->pluck('product_id'))->get();
        $subtotals = [];
        foreach ($cartItems as $cartItem) {
            $product = $products->firstWhere('id', $cartItem->product_id);
            if ($product) {
                $subtotals[] = $product->price * $cartItem->quantity;
            }
        }

        // 4. 合計金額を計算
        $total = 0;
        foreach ($subtotals as $subtotal) {
            $total += $subtotal;
        }

        // 4. 【重要】データベースのトランザクションを開始
        // 注文(orders)と明細(order_items)の保存は「両方成功」か「両方失敗」のどちらかでなければならないため
        DB::beginTransaction();

        try {
            // 5. orders テーブルにレコードを挿入し、発行された注文IDを取得
            $orderId = DB::table('orders')->insertGetId([
                'user_id'    => $user->id,
                'sumprice'   => $total,
                'order_date' => now(), 
            ]);

            // 6. order_items テーブルにカートの商品を1つずつ保存
            foreach ($cartItems as $item) {
                DB::table('order_items')->insert([
                    'order_id'   => $orderId,
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                ]);

                // 商品の在庫数を減らす（例: products テーブルの stock カラムを更新）
                DB::table('products')->where('id', $item->product_id)->decrement('stock', $item->quantity);
            }

            // 7. 購入が完了したので、このユーザーのカートを空にする
            DB::table('cart_items')->where('user_id', $user->id)->delete();

            // すべての処理が成功したので、データベースに変更を確定反映（コミット）
            DB::commit();

            $userId = $user->id;
            dispatch(function () use ($userId) {
                $groqService = app(\App\Services\GroqRecommendationService::class);
                $groqService->calculateAndSaveScores($userId);
            })->afterResponse();

            // 8. 完了画面へリダイレクト（例: サンクスページなど）
            return view('purchase.complete');

        } catch (Exception $e) {
            // 途中でエラーが発生した場合、ここまでのDB操作をすべて無かったことにする（ロールバック）
            DB::rollBack();
        }
    }
}
