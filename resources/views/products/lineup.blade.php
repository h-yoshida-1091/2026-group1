<h1>商品一覧</h1>
@foreach ($products as $product)
    <div style="border:1px solid #000; margin-bottom:20px; padding:10px; display:flex;">
        
        <!-- 左：画像 -->
        <div style="width:300px;">
            <img src="" alt="商品画像" style="width:100%;">
        </div>

        <!-- 右：商品情報 -->
        <div style="margin-left:20px;">
            <h3>{{ $product->name }}</h3>

            <p>在庫数：{{ $product->stock }}</p>
            <p>価格：{{ $product->price }}円</p>
             
            <br>

            <!-- カートボタン -->
            <button>カートに入れる</button>

            <br><br>
            <!-- 購入ボタン -->
            <button>今すぐ購入</button>

            <br><br>
            <!-- 詳細リンク -->
            <a href="/products/detail?id={{ $product->id }}">詳細へ</a>

        </div>
    </div>
@endforeach