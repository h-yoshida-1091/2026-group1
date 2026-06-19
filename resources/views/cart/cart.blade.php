@vite(['resources/css/cart.css'])

<h1 class="title">カート</h1>

<a href="/products" class="product-link">商品一覧</a>

<?php $total = 0; ?>

@foreach ($products as $product)

    <!--?php $total += $product->price * $product->quantity; ?-->

    <div class="cart-item">

        <!-- 商品画像 -->
        <div class="image-area">
            <img src="{{ $product->image }}" alt="商品画像">
        </div>

        <!-- 商品情報 -->
        <div class="info-area">

            <div class="product-name">
                {{ $product->name }}
            </div>

            <div class="quantity-area">
                個数
                <button type="button">－</button>

                <span>{{ $product->quantity }}</span>

                <button type="button">＋</button>
            </div>

            <div class="price-area">
                {{ $product->price }}円
            </div>

            <div class="subtotal-area">
                小計
                {{ $product->price * $product->quantity }}円
            </div>

            <form action="/cart/delete" method="post">
                @csrf

                <input type="hidden"
                       name="id"
                       value="{{ $product->id }}">

                <button type="submit">
                    削除
                </button>
            </form>

        </div>

    </div>

@endforeach

<div class="total">
    合計 ¥{{ number_format($total) }}
</div>

<div class="purchase">
    <button type="submit">
        購入確認
    </button>
</div>