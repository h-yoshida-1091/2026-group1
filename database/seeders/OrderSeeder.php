<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //データリセット
        DB::table('orders')->truncate();

        //データ挿入
        DB::table('orders')->insert([
            [
                'user_id' => 2,
                'sumprice' => 7000,
                'order_date' => '2026-06-26 12:00:00'
            ],
        ]);

    }
}