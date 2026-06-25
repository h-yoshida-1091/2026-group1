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
        DB::table('users')->insert([
            [
                'name' => 'TBC',
                'email' => 'TBC@tbc.com',
                'password' => Hash::make('password'), // パスワードは暗号化する
                'postal_code' => '1030024',
                'address' => '東京都中央区日本橋小舟町',
                'role' => null,
            ],
            [
                'name' => '管理者',
                'email' => 'Role@role.com',
                'password' => Hash::make('rolerole4649'),
                'postal_code' => '1310045',
                'address' => '東京都墨田区押上１丁目１－２',
                'role' => '管理者です',
            ],
        ]);
    }
}
