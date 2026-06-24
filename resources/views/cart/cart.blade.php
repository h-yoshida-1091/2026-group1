@include('layouts.header')

<title>カート</title>

<link rel="stylesheet" href="{{ asset('css/cart.css') }}">

<body>

    @include('header')

    <h1 class="title">カート</h1>

    @if (session('error'))
    <p style="color:red;">{{ session('error') }}</p>
    @endif

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

 
        <!-- 商品情報 -->
        <div class="info-area">
            <div class="product-name">
                {{ $product->name }}
            </div>

            <div class="quantity-area">
                個数

                <!-- 減らす -->
                <form action="/cart/decrease" method="post">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit"
                        {{ $product->quantity <= 0 ? 'disabled' : '' }}>
                        −
                    </button>
                </form>

                <span>{{ $product->quantity }}</span>

                <!-- 増やす -->
                <form action="/cart/increase" method="post">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit">
                        ＋
                    </button>
                </form>

            </div>

        </div>

            <div class="price-area">
                {{ number_format($product->price) }}円
            </div>

            <div class="subtotal-area">
                小計 {{ number_format($product->price * $product->quantity) }}円
            </div>

            <!-- 削除 -->
            <form action="/cart/delete" method="post" class="delete-form">
                @csrf
                <input type="hidden" name="id" value="{{ $product->id }}">
                <button type="submit">
                    削除
                </button>
            </form>

        </div>

    </div>

    @endforeach

    <!-- 合計金額 -->
    <div class="total">
        合計 ¥{{ number_format($total) }}
    </div>

    <!-- 下部ボタン -->
    <div class="purchase">

        <a href="/products" class="back-products-btn">
            商品一覧へ戻る
        </a>

        <form action="/purchase/confirm" method="post">
            @csrf

            @foreach ($products as $product)

            <input type="hidden"
                name="products[{{ $product->id }}][id]"
                value="{{ $product->id }}">

            <input type="hidden"
                name="products[{{ $product->id }}][quantity]"
                value="{{ $product->quantity }}">

            @endforeach

            <button type="submit" class="purchase-btn">
                購入確認
            </button>

        </form>

    </div>

</body>

@include('layouts.footer')