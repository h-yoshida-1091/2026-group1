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
                'stock' => 2
            ],
            [
                'category_id' => 2,
                'image_id' => 2,
                'name' => 'クルアーン',
                'description' => 'イスラム教の聖典であり、神（アッラー）の言葉が預言者ムハンマドに啓示されたものです',
                'price' => 500,
                'stock' => 8
            ],
            [
                'category_id' => 3,
                'image_id' => 3,
                'name' => '旧約聖書',
                'description' => '紀元前4〜5世紀頃に成立し、イエス・キリスト誕生以前の神とイスラエル民族の関係を描く書物です',
                'price' => 2000,
                'stock' => 5
            ],
            [
                'category_id' => 4,
                'image_id' => 4,
                'name' => '新約聖書',
                'description' => 'イエス・キリストの生涯以降に成立し、キリストを通じた新しい契約と救いの道を記しています',
                'price' => 2000,
                'stock' => 7
            ],
            [
                'category_id' => 5,
                'image_id' => 5,
                'name' => 'ヴェーダ',
                'description' => 'ヴェーダは紀元前1000年頃から紀元前500年頃にかけてインドで編纂された一連の宗教文書で、バラモン教やヒンドゥー教の聖典として重要視されています',
                'price' => 2100,
                'stock' => 6
            ],
            [
                'category_id' => 5,
                'image_id' => 6,
                'name' => '叙事詩「ラーマーヤナ」',
                'description' => '古代インドの大長編叙事詩。ヒンドゥー教の聖典の一つであり、『マハーバーラタ』と並ぶインド2大叙事詩の一つである。',
                'price' => 1800,
                'stock' => 4
            ],
            [
                'category_id' => 5,
                'image_id' => 7,
                'name' => '叙事詩「マハーバーラタ」',
                'description' => '伝説のリシ（聖仙）ヴィヤーサが著作したとされるバラタ族にまつわる大叙事詩',
                'price' => 1500,
                'stock' => 15
            ],
            [
                'category_id' => 3,
                'image_id' => 8,
                'name' => '出エジプト記',
                'description' => '旧約聖書の第2の書で、モーセがイスラエル人をエジプトから導き出す物語を中心に描かれた書物です',
                'price' => 2500,
                'stock' => 3
            ],
            [
                'category_id' => 6,
                'image_id' => 9,
                'name' => 'ネクロノミコン',
                'description' => 'クトゥルフ神話に登場する禁忌の書物です。クトゥルフ神話の物語や宇宙の真理、魔術に関する知識が含まれている。',
                'price' => 5000,
                'stock' => 9
            ],
            [
                'category_id' => 6,
                'image_id' => 10,
                'name' => 'エイボンの書',
                'description' => '古代大陸ハイパーボリアの大魔導士エイボンによって記された魔術書「暗澹たる不気味な神話、邪悪かつ深遠な呪文、儀式、典礼の一大集成」と表現される。神々の歴史、異次元の知識、そして魔術や儀式の方法などが収録された禁忌の書',
                'price' => 3000,
                'stock' => 9
            ],
            [
                'category_id' => 6,
                'image_id' => 11,
                'name' => 'ゴエティア',
                'description' => '『レメゲトン（ソロモンの小さな鍵）』の第一書で、72柱の悪魔を呼び出し制御する方法を記した文献本書には、悪魔の名前、性格、能力、印章（シジル）、魔法円の作り方、呪文などが詳細に記載されており、悪魔学や西洋魔術の研究において重要な資料とされています',
                'price' => 3100,
                'stock' => 9
            ],
            [
                'category_id' => 7,
                'image_id' => 12,
                'name' => 'ドグラ・マグラ',
                'description' => '単なる推理小説にとどまらず、心理学、犯罪学、遺伝学、宗教哲学などを横断する思想的実験の書とも評価されています。「読むと気が狂う」と称されるほどの難解さと独創性で知られています。単なる難解さだけでなく、読者の認識構造を揺さぶる仕掛けが施されており、文学史上でも特異な存在として評価されています',
                'price' => 2513,
                'stock' => 0
            ],
            [
                'category_id' => 7,
                'image_id' => 13,
                'name' => '黒死館殺人事件',
                'description' => '名探偵が広壮な屋敷内で起こる連続殺人事件に挑む、という探偵小説の定番のものであるが、本作の特徴は晦渋な文体、ルビだらけの特殊な専門用語の多用、そして何より、殺人事件の実行、解決としては非現実かつ饒舌すぎる神秘思想・占星術・異端神学・宗教学・物理学・医学・薬学・紋章学・心理学・犯罪学・暗号学などの夥しい衒学趣味（ペダントリー）である',
                'price' => 1283,
                'stock' => 15
            ],
            [
                'category_id' => 7,
                'image_id' => 14,
                'name' => '虚無への供物',
                'description' => '本作は、事件が起きる前に犯人を推理するという荒唐無稽な設定や、複雑な密室殺人の構造、そして「痛ましい虚無感」と「冷ややかな告発の響き」を読者に与える点で高く評価されています',
                'price' => 3187,
                'stock' => 15
            ],
            [
                'category_id' => 1,
                'image_id' => 15,
                'name' => '無量義経',
                'description' => '法華経の前段として釈迦が説いた大乗経典で、無限の意味を持つ教えを示す序説的経典,釈迦が法華経を説く直前に、マガダ国王舎城郊外の霊鷲山で説いた経典で、「無量義」とは「数限りない意味を持つ教え」を意味します',
                'price' => 100,
                'stock' => 8
            ],
            [
                'category_id' => 1,
                'image_id' => 16,
                'name' => '妙法蓮華経',
                'description' => '法華経の正式名称であり、すべての生き物を救い、仏の境地へ導く教えを説く大乗仏教の経典です。サンスクリット語で Saddharmapundarika-sutra（サッダルマ・プンダリーカ・スートラ） と呼ばれ、鳩摩羅什によって漢訳されました',
                'price' => 300,
                'stock' => 1
            ],
            [
                'category_id' => 1,
                'image_id' => 17,
                'name' => '観普賢菩薩行法経',
                'description' => '普賢菩薩の行いを観想し、心を清めて仏道に近づくための修行法を説いた法華経の結経です。法華経の教えを受けて普賢菩薩の行いを観る修行法を示しています。この経典は、日常生活での心の清浄化や懺悔を通じて、仏の智慧と一致する心を養うことを目的としています',
                'price' => 300,
                'stock' => 20
            ],
        ]);

        // 4. 外部キー制約を元に戻す
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
