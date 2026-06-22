@include('layouts.header')
<h1>商品一覧</h1>
@foreach ($products as $product)
    <div style="border:1px solid #000; margin-bottom:20px; padding:10px; display:flex;">
        
        <!-- 左：画像 -->
        <div style="width:300px;">
            <img src="{{ $product->image_url }}" alt="商品画像" style="width:100%;">
        </div>

        <!-- 右：商品情報 -->
        <div style="margin-left:20px;">
            <h3>{{ $product->name }}</h3>

            <p>在庫数：{{ $product->stock }}</p>
            <p>価格：{{ $product->price }}円</p>
             
            <br>


            <!-- カートボタン -->
            <form action="/cart/add" method="post">
                @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="quantity" value="1">
            <button type="submit">カートに入れる</button>
            </form>



            <br><br>
            <!-- 購入ボタン(同上) -->
            <a href="/purchase/direct?id={{ $product->id }}" class="btn-buy" style="display:inline-block; padding: 5px 10px; border: 1px solid #ccc; background-color: #ff9900; text-decoration: none; color: white; font-weight: bold;">
                今すぐ購入
            </a>

            <br><br>
            <!-- 詳細リンク -->
            <a href="/products/detail?id={{ $product->id }}">詳細へ</a>

        </div>
    </div>
@endforeach