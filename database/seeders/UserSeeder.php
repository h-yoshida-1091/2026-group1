<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // 1. 外部キー制約を一時的に無効化（エラー防止）
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 2. 既存データを削除し、オートインクリメントを1にリセット
        DB::table('users')->truncate();

        DB::table('users')->insert([
            [
                'name' => '管理者',
                'email' => 'Role@role.com',
                'password' => Hash::make('rolerole4649'),
                'postal_code' => '9994333',
                'address' => '山形県尾花沢市銀山新畑８５',
                'role' => '管理者',
            ],
            [
                'name' => '商品 売買',
                'email' => 'fgroup.shoping@gmail.com',
                'password' => Hash::make('baibai6161'),
                'postal_code' => '0590551',
                'address' => '北海道登別市登別温泉町６５',
                'role' => null,
            ],
            [
                'name' => '黒峰 凛',
                'email' => 'rinrin@gmail.com',
                'password' => Hash::make('kuroneko184'),
                'postal_code' => '4130005',
                'address' => '静岡県熱海市春日町１－２',
                'role' => null,
            ],
            [
                'name' => '春宮 航',
                'email' => 'watawata@gmail.com',
                'password' => Hash::make('spring5151'),
                'postal_code' => '7900842',
                'address' => '愛媛県松山市道後湯之町５－６',
                'role' => null,
            ],
            [
                'name' => '海森 夏美',
                'email' => 'ocean@gmail.com',
                'password' => Hash::make('summer5151'),
                'postal_code' => '3771711',
                'address' => '群馬県吾妻郡草津町草津',
                'role' => null,
            ],
            [
                'name' => '秋川 実',
                'email' => 'donguri@gmail.com',
                'password' => Hash::make('fall5151'),
                'postal_code' => '6511401',
                'address' => '兵庫県神戸市北区有馬町８３３',
                'role' => null,
            ],
            [
                'name' => '冬木 こころ',
                'email' => 'heart@gmail.com',
                'password' => Hash::make('winter5151'),
                'postal_code' => '5092207',
                'address' => '岐阜県下呂市湯之島５７０',
                'role' => null,
            ],
            [
                'name' => '朝野 聡',
                'email' => 'clever@gmail.com',
                'password' => Hash::make('morning114'),
                'postal_code' => '2500631',
                'address' => '神奈川県足柄下郡箱根町仏石原１２８３－９７',
                'role' => null,
            ],
            [
                'name' => '宵崎 玖瑠美',
                'email' => 'kurukuru@gmail.com',
                'password' => Hash::make('midnight114'),
                'postal_code' => '8740822',
                'address' => '大分県別府市観見寺１',
                'role' => null,
            ],
        ]);

        // 4. 外部キー制約を元に戻す
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    }
}
