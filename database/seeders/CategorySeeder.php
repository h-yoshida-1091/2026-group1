<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //データリセット
        DB::table('categories')->truncate();

        //データ挿入
        DB::table('categories')->insert([
            [
                'name' => '仏教'
            ],
            [
                'name' => 'イスラム教'
            ],
            [
                'name' => 'ユダヤ教'
            ],
            [
                'name' => 'キリスト教'
            ],
            [
                'name' => 'ヒンドゥー教'
            ],
            [
                'name' => '魔術書'
            ]
        ]);

    }
}