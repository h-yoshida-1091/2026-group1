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

        // 購入確認直前の在庫チェック
        $hasStockError = false;
        $errorMessages = [];

        foreach ($cartItems as $cartItem) {
            $product = $products->firstWhere('id', $cartItem->product_id);

            if ($product && $cartItem->quantity > $product->stock) {
                $hasStockError = true;
                $errorMessages[] = $product->name . 'の在庫上限（' . $product->stock . '個）に達しました。';

                // カート内の実際の数量も、商品テーブルの最大在庫数に強制補正して即時保存
                Cart_item::where('user_id', $user->id)
                    ->where('product_id', $product->id)
                    ->update(['quantity' => $product->stock]);
            }
        }

        // 1件でも他ユーザーの購入による在庫減少（超過）を検知したら、カート画面に押し戻す
        if ($hasStockError) {
            return redirect('/cart')->with('errors_array', $errorMessages);
        }

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
            'purchaseType' => 'cart',
            'user' => $user,
            'cartItems' => $cartItems,
            'products' => $products,
            'subtotals' => $subtotals,
            'total' => $total,
        ]);
    }

    public function nowPurchase(Request $request)
    {
        // セッションからログイン中のユーザー情報を取得
        $user = Auth::user();

        if (!$user) {
            return redirect('/login')->with('error_message', '今すぐ購入機能を利用するにはログインが必要です。');
        }

        $productId = $request->input('products.0.id');
        $quantity = $request->input('products.0.quantity');

        $product = Product::findOrFail($productId);

        // 今すぐ購入でも在庫上限を超えていた場合は自動適用
        if ($quantity > $product->stock) {
            session()->flash('now_purchase_error', $product->name . 'の在庫上限（' . $product->stock . '個）に達しました。');
            // リストに追加しない（空のコレクションを渡す）
            $cartItems = collect([]);
            $products = collect([]);
            $subtotals =[];
            $total = 0;
        }else {
            // 通常通りリストに追加する
            $cartItems = collect([
                (object)[
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                ]
            ]);

            $products = collect([$product]);
            $subtotals = [ $product->price * $quantity ];
            $total = $subtotals[0];
        }
        
        return view('purchase.confirm', [
            'purchaseType' => 'now',
            'user' => $user,
            'cartItems' => $cartItems,
            'products' => $products,
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

        if ($request->purchase_type === 'now') {

            $cartItems = collect([
                (object)[
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity,
                ]
            ]);
        } else {

            $cartItems = Cart_item::where('user_id', $user->id)->get();
        }

        $products = Product::whereIn('id', $cartItems->pluck('product_id'))
            ->get();

        $total = 0;

        foreach ($cartItems as $item) {

            $product = $products->firstWhere('id', $item->product_id);

            if ($product) {
                $total += $product->price * $item->quantity;
            }
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

            if ($request->purchase_type === 'cart') {
                // 7. 購入が完了したので、このユーザーのカートを空にする
                DB::table('cart_items')->where('user_id', $user->id)->delete();
            }

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

    public function getRecommendedProducts($userId)
    {
        $scores = DB::table('recommend_scores')
            ->where('user_id', $userId)
            ->orderByDesc('score')
            ->limit(3)
            ->get();

        $products = [];

        foreach ($scores as $score) {

            $product = DB::table('products')
                ->where('id', $score->product_id)
                ->first();

            if ($product) {

                $image = DB::table('product_images')
                    ->where('product_id', $product->id)
                    ->value('image_url');

                $product->score = $score->score;
                $product->image_url = $image;

                $products[] = $product;
            }
        }

        return $products;
    }
}
