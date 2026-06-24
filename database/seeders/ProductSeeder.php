<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. 外部キー制約を一時的に無効化（エラー防止）
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 2. 既存データを削除し、オートインクリメントを1にリセット
        DB::table('products')->truncate();

        // 3. データの挿入
        DB::table('products')->insert([
            [
                'category_id' => 1,
                'image_id' => 1,
                'name' => '真言宗 常用経典',
                'description' => '真言宗の常用経典として最も重要なのは「理趣経」であり、正式名称は「大楽金剛不空真実三昧耶経・般若波羅蜜多理趣分」です',
                'price' => 2500,
                'stock' => 10
            ],
            [
                'category_id' => 1,
                'image_id' => 2,
                'name' => 'イスラム経典',
                'description' => 'イスラム教の聖典であり、神（アッラー）の言葉が預言者ムハンマドに啓示されたものです',
                'price' => 980,
                'stock' => 8
            ],
            [
                'category_id' => 1,
                'image_id' => 3,
                'name' => '旧約聖書',
                'description' => '紀元前4〜5世紀頃に成立し、イエス・キリスト誕生以前の神とイスラエル民族の関係を描く書物です',
                'price' => 1200,
                'stock' => 5
            ],
            [
                'category_id' => 2,
                'image_id' => 4,
                'name' => '新約聖書',
                'description' => 'イエス・キリストの生涯以降に成立し、キリストを通じた新しい契約と救いの道を記しています',
                'price' => 2000,
                'stock' => 7
            ],
            [
                'category_id' => 2,
                'image_id' => 5,
                'name' => 'ヴェーダ',
                'description' => 'ヴェーダは紀元前1000年頃から紀元前500年頃にかけてインドで編纂された一連の宗教文書で、バラモン教やヒンドゥー教の聖典として重要視されています',
                'price' => 1000,
                'stock' => 6
            ],
            [
                'category_id' => 2,
                'image_id' => 6,
                'name' => '叙事詩「ラーマーヤナ」',
                'description' => '古代インドの大長編叙事詩。ヒンドゥー教の聖典の一つであり、『マハーバーラタ』と並ぶインド2大叙事詩の一つである。',
                'price' => 1800,
                'stock' => 4
            ],
            [
                'category_id' => 3,
                'image_id' => 7,
                'name' => '叙事詩「マハーバーラタ」',
                'description' => '伝説のリシ（聖仙）ヴィヤーサが著作したとされるバラタ族にまつわる大叙事詩',
                'price' => 780,
                'stock' => 15
            ],
            [
                'category_id' => 3,
                'image_id' => 8,
                'name' => '出エジプト記',
                'description' => '旧約聖書の第2の書で、モーセがイスラエル人をエジプトから導き出す物語を中心に描かれた書物です',
                'price' => 3400,
                'stock' => 3
            ],
            [
                'category_id' => 3,
                'image_id' => 9,
                'name' => 'ネクロノミコン',
                'description' => 'クトゥルフ神話に登場する魔導書の1つであり、特に有名な禁忌の書物です',
                'price' => 2200,
                'stock' => 9
            ],
        ]);

        // 4. 外部キー制約を元に戻す
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
