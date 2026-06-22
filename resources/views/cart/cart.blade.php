<!DOCTYPE html>
<html lang="ja">
 
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>カート</title>
 
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}">
</head>
 
<body>
 
    <h1 class="title">カート</h1>
 
    @if (session('error'))
        <p style="color:red;">{{ session('error') }}</p>
    @endif
 
    <a href="/products" class="product-link">商品一覧</a>
 
    @php
        $total = 0;
    @endphp
 
    @foreach ($products as $product)
 
        @php
            $total += $product->price * $product->quantity;
        @endphp
 
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
 
                <!-- 減らすボタン -->
                <form action="/cart/decrease" method="post" style="display:inline;">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit" {{ $product->quantity <= 0 ? 'disabled' : '' }}>－</button>
                </form>
 
                <span>{{ $product->quantity }}</span>
 
                <!-- 増やすボタン -->
                <form action="/cart/increase" method="post" style="display:inline;">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit">＋</button>
                </form>
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
        <form action="/purchase/confirm" method="post">
            @csrf

            @foreach ($products as $product)

            <input type="hidden"
                name="products[{{ $product->id }}][id]"
                value="{{ $product->id }}">

            <input type="hidden"
                name="products[{{ $product->id }}][quantity]"
                class="hidden-quantity-{{ $product->id }}"
                value="{{ $product->quantity }}">

            @endforeach

            <button type="submit">
                購入確認
            </button>
        </form>
    </div>
 
</body>
 
</html>
 
 