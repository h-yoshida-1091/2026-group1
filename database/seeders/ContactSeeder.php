<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. 外部キー制約を一時的に無効化
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 2. 既存データの削除
        DB::table('contacts')->truncate();

        // 3. データの挿入（各ステータス2件ずつ、同じユーザーは24時間以上の間隔を空ける）
        DB::table('contacts')->insert([
            // --- 未対応 (2件) ---
            [
                'name' => '商品 売買',
                'email' => 'fgroup.shoping@gmail.com',
                'subject' => '商品の落丁について',
                'message' => '昨日届いた「真言宗 常用経典」ですが、32ページから40ページまでが丸ごと抜け落ちていました。交換手続きについて教えてください。',
                'status' => '未対応',
                'previous_status' => null,
                'priority' => 3, // 高い優先度
                'created_at' => '2026-06-20 10:00:00',
                'updated_at' => '2026-06-20 10:00:00',
            ],
            [
                'name' => '黒峰 凛',
                'email' => 'rinrin@gmail.com',
                'subject' => 'パスワード変更ができない',
                'message' => 'マイページからパスワードの変更を行おうとしたのですが、エラーが出てしまい変更できません。システム不具合でしょうか？',
                'status' => '未対応',
                'previous_status' => null,
                'priority' => 2,
                'created_at' => '2026-06-21 14:30:00',
                'updated_at' => '2026-06-21 14:30:00',
            ],

            // --- 対応済 (2件) ---
            [
                'name' => '春宮 航',
                'email' => 'watawata@gmail.com',
                'subject' => '配送先の変更依頼',
                'message' => '注文番号#10023の配送先住所を間違えてしまいました。正しい住所はマイページに登録している愛媛県の住所になります。変更をお願いします。',
                'status' => '対応済',
                'previous_status' => null,
                'priority' => 2,
                'created_at' => '2026-06-15 09:15:00',
                'updated_at' => '2026-06-15 11:00:00',
            ],
            [
                'name' => '海森 夏美',
                'email' => 'ocean@gmail.com',
                'subject' => '領収書の発行について',
                'message' => '購入した「新約聖書」の領収書をPDFでいただくことは可能でしょうか。宛名は「海森」でお願いいたします。',
                'status' => '対応済',
                'previous_status' => null,
                'priority' => 1,
                'created_at' => '2026-06-16 18:20:00',
                'updated_at' => '2026-06-17 10:00:00',
            ],

            // --- スパム (2件) ---
            [
                'name' => '秋川 実',
                'email' => 'donguri@gmail.com',
                'subject' => '【重要】格安でアクセス数を増やす方法',
                'message' => '貴店のウェブサイトのアクセス数を現在の3倍にする特別なご案内です。詳細は以下のリンクよりご確認ください。http://example.com/spam',
                'status' => 'スパム',
                'previous_status' => '未対応', // スパムの場合：未対応
                'priority' => 1,
                'created_at' => '2026-06-22 03:00:00',
                'updated_at' => '2026-06-22 09:15:00',
            ],
            [
                'name' => '冬木 こころ',
                'email' => 'heart@gmail.com',
                'subject' => 'dsfghfjdhsgd',
                'message' => 'dgasnfnasgnjgmgomkmosgnjisdv',
                'status' => 'スパム',
                'previous_status' => '未対応', // スパムの場合：未対応
                'priority' => 1,
                'created_at' => '2026-06-23 01:45:00',
                'updated_at' => '2026-06-23 08:30:00',
            ],

            // --- ゴミ箱 (2件) ---
            // 💡 制限対策：「商品 売買」さんは1件目から5日空けているので24時間制限をクリア
            [
                'name' => '商品 売買',
                'email' => 'fgroup.shoping@gmail.com',
                'subject' => 'テスト送信です',
                'message' => 'これはシステムテストのためのメッセージです。無視して削除していただいて構いません。よろしくお願いいたします。',
                'status' => 'ゴミ箱',
                'previous_status' => '未対応', // ゴミ箱の場合：未対応か対応済
                'priority' => 1,
                'created_at' => '2026-06-24 12:00:00',
                'updated_at' => '2026-06-25 15:00:00',
            ],
            // 💡 制限対策：「黒峰 凛」さんは2件目から5日空けているので24時間制限をクリア
            [
                'name' => '黒峰 凛',
                'email' => 'rinrin@gmail.com',
                'subject' => '間違えて連打してしまいました',
                'message' => '先ほど送った問い合わせの内容ですが、ブラウザの戻るボタンを押してしまい重複して送信されたかもしれません。こちらは破棄してください。',
                'status' => 'ゴミ箱',
                'previous_status' => '対応済', // ゴミ箱の場合：未対応か対応済
                'priority' => 1,
                'created_at' => '2026-06-26 11:00:00',
                'updated_at' => '2026-06-26 11:30:00',
            ],
        ]);

        // 4. 外部キー制約を元に戻す
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}