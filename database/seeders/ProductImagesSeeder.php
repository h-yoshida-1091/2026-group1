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
            ['image_url' => 'https://m.media-amazon.com/images/I/41c6YdFkqOL._SY445_SX342_.jpg'],
            ['image_url' => 'https://tse1.explicit.bing.net/th/id/OIP.d5HHOj7X0KDhybShAy7U1gHaJD?cb=thfc1falcon2&rs=1&pid=ImgDetMain&o=7&rm=3'],
            ['image_url' => 'https://wlpm.xsrv.jp/wings/images/s105902.jpg'],
            ['image_url' => 'https://m.media-amazon.com/images/I/51E0eytLZ0L._SL1000_.jpg'],
            ['image_url' => 'https://assets.st-note.com/production/uploads/images/121608791/rectangle_large_type_2_3c4700cf584160ab8962bd0b00034a6b.png?fit=bounds&quality=85&width=1280'],
            ['image_url' => 'https://pictures.abebooks.com/inventory/30878578928.jpg'],
            ['image_url' => 'https://baseec-img-mng.akamaized.net/images/item/origin/14785a789e8bb22348ceba3cac5bd4f3.jpg?imformat=generic&q=90&im=Resize,width=1200,type=normal'],
            ['image_url' => 'https://thf.bing.com/th/id/R.cba511263b697cde115c013ba61db5d1?rik=D95jg0VzGD%2f0Zg&riu=http%3a%2f%2fpaparacha.net%2fwp-content%2fuploads%2f2019%2f10%2fd17vhyz-cad6c148-6042-40c7-a62e-7ada9b8957f5.jpg&ehk=MexytnvYCIMFgwL0kYm9GA1czuodvUHdNo2SabRh%2fko%3d&risl=&pid=ImgRaw&r=0'],
            ['image_url' => 'https://th.bing.com/th/id/R.1eba0570c290126c19f9f46fd5f9cf8b?rik=hgILK21hHDPMKg&riu=http%3a%2f%2fwww.shinkigensha.co.jp%2fbooks%2fimages%2f9784775306321.jpg&ehk=YZYpExry4a11Ur8eamMNQ8eCrQTv%2bclswBHZAKEYUm0%3d&risl=&pid=ImgRaw&r=0'],
            ['image_url' => 'https://img.aucfree.com/q447186408.1.jpg'],
            ['image_url' => 'https://th.bing.com/th/id/OIP.Yx9iWcTCCBuw-aC4KqYETgAAAA?w=197&h=316&c=7&r=0&o=7&dpr=1.5&pid=1.7&rm=3'],
            ['image_url' => 'https://shop.r10s.jp/book/cabinet/9054/9784309409054_1_3.jpg'],
            ['image_url' => 'https://www.natsume-books.com/i_item/2023/08/424948.jpg'],
            ['image_url' => 'https://assets.st-note.com/production/uploads/images/95184580/picture_pc_c3e1df92b22b33243fa34fa7c97b0261.jpg?width=800'],
            ['image_url' => 'https://p1-e6eeae93.imageflux.jp/c!/f=webp:auto,a=2,w=920,h=920,b=ffffff00/giovanni-letterpress/6ff1c223d910f4d51d21.jpeg'],
            ['image_url' => 'https://m.media-amazon.com/images/I/81e2fs8A4JL._SY385_.jpg']
        ]);

    }
}
