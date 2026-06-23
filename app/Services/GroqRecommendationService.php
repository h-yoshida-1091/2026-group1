<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class GroqRecommendationService
{
    /**
     * 指定されたユーザーのおすすめスコアをGroq AIを使って計算し、DBに保存する
     */
    public function calculateAndSaveScores(int $userId)
    {
        // 1. ユーザーの行動データ（お気に入り商品名、購入商品名）を取得
        $favoriteProductNames = DB::table('favorites')
            ->join('products', 'favorites.product_id', '=', 'products.id')
            ->where('favorites.user_id', $userId)
            ->pluck('products.name')
            ->toArray();

        //order_items テーブルの構造に合わせて結合を行い、購入した商品の名前を取得
        $purchasedProductNames = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.user_id', $userId)
            ->pluck('products.name')
            ->toArray();

        // 2. 全商品リストをテキストデータ化する
        $allProducts = Product::select('id', 'name', 'category_id')->get();
        
        $productsText = "";
        foreach ($allProducts as $product) {
            $productsText .= "ID: {$product->id}, 商品名: {$product->name}\n";
        }

        // 3. AIに送る指示文（プロンプト）を作成
        $favoritesStr = empty($favoriteProductNames) ? 'なし' : implode(', ', $favoriteProductNames);
        $purchasedStr = empty($purchasedProductNames) ? 'なし' : implode(', ', $purchasedProductNames);

        $systemPrompt = "あなたはECサイトの優秀なレコメンドエンジンです。ユーザーの好みを分析し、全商品に対して『おすすめ度スコア（1〜100点）』を計算してください。返答は必ず指定されたJSONフォーマットのみ（余計な挨拶や説明文は一切禁止）で出力してください。";

        $userPrompt = "【ユーザーの行動データ】\n";
        $userPrompt .= "お気に入りした商品: {$favoritesStr}\n";
        $userPrompt .= "過去に購入した商品: {$purchasedStr}\n\n";
        $userPrompt .= "【全商品リスト】\n{$productsText}\n";
        $userPrompt .= "【出力フォーマット】\n";
        $userPrompt .= "必ず以下のような、商品IDをキー、スコア(整数)を値にした単一のJSONオブジェクトにしてください。データにない商品IDを含めてはいけません。\n";
        $userPrompt .= '{"1": 85, "2": 40, "3": 95}';

        // 4. Groq APIへリクエストを送信
        $apiKey = env('GROQ_API_KEY');
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.1-8b-instant', // 高速かつ無料枠で動くモデル
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt]
                ],
                // 💡 確実にJSON形式で返却させるためのGroqの設定
                'response_format' => ['type' => 'json_object'],
                'temperature' => 0.2 // 値を低くして、突飛な回答を防ぎ安定させる
            ]);

            if ($response->failed()) {
                Log::error('Groq API 通信エラー: ' . $response->body());
                return false;
            }

            // 5. 返ってきたJSONデータを解析（パース）
            $result = $response->json();
            $aiContent = $result['choices'][0]['message']['content'] ?? '{}';
            $scores = json_decode($aiContent, true); // 連想配列にする例: [1 => 85, 2 => 40]

            if (empty($scores)) {
                Log::warning('Groqからのスコアデータが空、または不正です。内容: ' . $aiContent);
                return false;
            }

            // 6. データベース（recommend_scoresテーブル）に保存・更新
            DB::transaction(function () use ($userId, $scores) {
                foreach ($scores as $productId => $score) {
                    // 念のため商品IDがデータベースに実在するかチェック
                    if (Product::where('id', $productId)->exists()) {
                        DB::table('recommend_scores')->updateOrInsert(
                            ['user_id' => $userId, 'product_id' => $productId],
                            [
                                'score' => (int)$score,
                                'updated_at' => now(),
                                'created_at' => now() // 新規作成時のみ適用されます
                            ]
                        );
                    }
                }
            });

            return true;

        } catch (\Exception $e) {
            Log::error('Groqレコメンド処理で例外発生: ' . $e->getMessage());
            return false;
        }
    }
}