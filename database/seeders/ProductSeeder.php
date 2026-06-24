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
                'name' => '真言宗　常用経典',
                'description' => 'Javaの入門書です',
                'price' => 2500,
                'stock' => 10
            ],
            [
                'category_id' => 1,
                'image_id' => 2,
                'name' => 'MLB Fun',
                'description' => 'メジャーリーグを楽しむ本',
                'price' => 980,
                'stock' => 8
            ],
            [
                'category_id' => 1,
                'image_id' => 3,
                'name' => '料理BOOK!',
                'description' => '料理レシピ本です',
                'price' => 1200,
                'stock' => 5
            ],
            [
                'category_id' => 2,
                'image_id' => 4,
                'name' => 'なつかしのアニメシリーズ',
                'description' => '懐かしアニメのDVD',
                'price' => 2000,
                'stock' => 7
            ],
            [
                'category_id' => 2,
                'image_id' => 5,
                'name' => 'The Racer',
                'description' => 'カーレース映画',
                'price' => 1000,
                'stock' => 6
            ],
            [
                'category_id' => 2,
                'image_id' => 6,
                'name' => 'Space Wars 3',
                'description' => 'SFアクション映画',
                'price' => 1800,
                'stock' => 4
            ],
            [
                'category_id' => 3,
                'image_id' => 7,
                'name' => 'パズルゲーム',
                'description' => '楽しいパズルゲーム',
                'price' => 780,
                'stock' => 15
            ],
            [
                'category_id' => 3,
                'image_id' => 8,
                'name' => 'Invader Fighter',
                'description' => 'シューティングゲーム',
                'price' => 3400,
                'stock' => 3
            ],
            [
                'category_id' => 3,
                'image_id' => 9,
                'name' => 'Play the BasketBall',
                'description' => 'バスケットボールゲーム',
                'price' => 2200,
                'stock' => 9
            ],
        ]);

        // 4. 外部キー制約を元に戻す
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
