<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //データリセット
        DB::table('product_images')->truncate();

        //データ挿入
        DB::table('product_images')->insert([
            ['image_url' => 'https://www.touken-world.jp/wp/wp-content/uploads/2020/06/2eb442efdd1e633fa05aaa381b5c452d.jpg'],
            ['image_url' => 'https://m.media-amazon.com/images/I/71xxe+9NOhL._AC_UL320_.jpg'],
            ['image_url' => 'https://m.media-amazon.com/images/I/71mLlDqCGYL._AC_UL320_.jpg'],
            ['image_url' => 'https://m.media-amazon.com/images/I/81OsnwPxE1L._AC_UL320_.jpg'],
            ['image_url' => 'https://m.media-amazon.com/images/I/517UqB97iRL._AC_.jpg'],
            ['image_url' => '/images/Designer.png'],
            ['image_url' => '/images/puzzlegame.png'],
            ['image_url' => 'https://kotowaka.com/wp-content/uploads/2020/04/invader.jpg'],
            ['image_url' => 'https://img.atwiki.jp/niconicomugen/attach/4472/9059/062685000L.jpg'],
        ]);

    }
}
