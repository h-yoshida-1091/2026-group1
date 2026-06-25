<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqContactAnalysisService
{
    /**
     * お問い合わせの件名と本文をGroq AIを使って解析し、スパム判定と優先度を返す
     * * @param string $subject
     * @param string $message
     * @return array ['is_spam' => bool, 'priority' => int]
     */
    public function analyzeContact(string $subject, string $message): array
    {
        $apiKey = env('GROQ_API_KEY');

        // 安全対策: APIキーが設定されていない場合はデフォルト（スパムなし、優先度：中）を返す
        if (!$apiKey) {
            Log::warning('Groq API Key が設定されていません。デフォルト値で処理します。');
            return ['is_spam' => false, 'priority' => 2];
        }

        $systemPrompt = "あなたはカスタマーサポートの優秀な仕分けAI助手です。入力されたお問い合わせの「件名」と「本文」を解析し、以下の厳密なJSONフォーマットのみ（余計な挨拶や説明文は一切禁止）で出力してください。";

        $userPrompt = "【お問い合わせデータ】\n";
        $userPrompt .= "件名: {$subject}\n";
        $userPrompt .= "本文: {$message}\n\n";
        $userPrompt .= "【判定基準】\n";
        // ★ スパムの条件をより具体的に厳しく定義する
        $userPrompt .= "1. is_spam (boolean): 以下のいずれかの特徴に少しでも該当する場合は必ず true、それ以外（正常な質問・要望・エラー報告など）は false としてください。\n";
        $userPrompt .= "   - 意味をなさないランダムな文字列（例: 'asdf', 'czxvc', 'dcふぁzdv' など）\n";
        $userPrompt .= "   - 明らかなテスト送信と思われる不適切な内容\n";
        $userPrompt .= "   - 商業的な宣伝、広告、セール案内、キャンペーン告知、クーポンの配布、勧誘、一方的なURLの貼り付け、誹謗中傷、嫌がらせ\n";
        $userPrompt .= "(スパム判定は、かなり厳密に行い、誤判定を避けるために、少しでも怪しい場合は true としてください。)\n\n";

        $userPrompt .= "2. priority (integer): ユーザーの困窮度やビジネス上の重要度を 1〜3 の3段階で判定（3:大至急・システム障害・購入トラブル、2:通常の質問や手続き、1:急ぎではない要望・単なる感想など）。\n\n";
        $userPrompt .= "【出力フォーマット】\n";
        $userPrompt .= "必ず以下のような、指定されたキーを持つ単一のJSONオブジェクトにしてください。\n";
        $userPrompt .= '"is_spam": trueもしくはfalse, "priority": 1~3の整数';

        try {
            // 前回の実装に合わせたHTTPリクエスト送信方式（5秒タイムアウト追加でUX向上）
            $response = Http::timeout(5)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => 'llama-3.1-8b-instant', // 前回と同じ高速・安定モデル
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userPrompt]
                    ],
                    'response_format' => ['type' => 'json_object'],
                    'temperature' => 0.0 // 判定タスクのため0.0に固定し、常にブレない結果を出力
                ]);

            if ($response->failed()) {
                Log::error('Groq API お問い合わせ解析エラー: ' . $response->body());
                return ['is_spam' => false, 'priority' => 2];
            }

            // 返ってきたJSONデータをパース
            $result = $response->json();
            $aiContent = $result['choices'][0]['message']['content'] ?? '{}';
            Log::info('Groq AIからの生データ: ' . $aiContent);
            $analysis = json_decode($aiContent, true);

            if (empty($analysis)) {
                Log::warning('Groqからの解析データが空、または不正です。内容: ' . $aiContent);
                return ['is_spam' => false, 'priority' => 2];
            }

            return [
                'is_spam'  => (bool)($analysis['is_spam'] ?? false),
                'priority' => (int)($analysis['priority'] ?? 2)
            ];
        } catch (\Exception $e) {
            Log::error('Groqお問い合わせ解析処理で例外発生: ' . $e->getMessage());
            return ['is_spam' => false, 'priority' => 2];
        }
    }
}
