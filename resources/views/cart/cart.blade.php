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

        <!-- 中央エリア -->
        <div class="info-area">

            <div class="product-name">
                {{ $product->name }}
            </div>

            <div class="quantity-area">

                <span>個数</span>

                <form action="/cart/decrease" method="post">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit">−</button>
                </form>

                <span class="quantity-number">
                    {{ $product->quantity }}
                </span>

                <form action="/cart/increase" method="post">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit">＋</button>
                </form>

            </div>

            <div class="action-buttons">

                <form action="/purchase/now" method="POST">
                    @csrf

                    <input type="hidden"
                        name="products[0][id]"
                        value="{{ $product->id }}">

                    <input type="hidden"
                        name="products[0][quantity]"
                        value="{{ $product->quantity }}">

                    <button type="submit" class="buy-now-btn">
                        今すぐ購入
                    </button>
                </form>

                <form action="/cart/delete" method="post" class="delete-form">
                    @csrf
                    <input type="hidden" name="id" value="{{ $product->id }}">

                    <button type="submit" class="delete-btn">
                        削除
                    </button>
                </form>

            </div>

        </div>

        <!-- 右エリア -->
        <div class="price-info">

            <div class="price-area">
                ¥{{ number_format($product->price) }}
            </div>

            <div class="subtotal-area">
                小計 ¥{{ number_format($product->price * $product->quantity) }}
            </div>

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

@include('footer')