<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //データリセット
        DB::table('order_items')->truncate();

        //データ挿入
        DB::table('order_items')->insert([
            [
                'order_id' => 1,
                'product_id' => 1,
                'quantity' => 2
            ],
            [
                'order_id' => 1,
                'product_id' => 2,
                'quantity' => 4
            ],
        ]);

    }
}
             