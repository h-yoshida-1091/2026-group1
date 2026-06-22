<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>カート</title>

    <link rel="stylesheet" href="{{ asset('css/cart.css') }}">
</head>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        function updateTotal() {

            let total = 0;

            document.querySelectorAll('.cart-item').forEach(function(item) {

                const price = parseInt(
                    item.querySelector('.price-area').dataset.price
                );

                const quantity = parseInt(
                    item.querySelector('.quantity-input').value
                );

                const subtotal = price * quantity;

                item.querySelector('.subtotal').textContent =
                    subtotal.toLocaleString();

                total += subtotal;
            });

            document.getElementById('total-price').textContent =
                total.toLocaleString();
        }

        document.querySelectorAll('.cart-item').forEach(function(item) {

            const minusBtn = item.querySelector('.minus-btn');
            const plusBtn = item.querySelector('.plus-btn');
            const quantityInput = item.querySelector('.quantity-input');

            minusBtn.addEventListener('click', function() {

                let quantity = parseInt(quantityInput.value);

                if (quantity > 1) {
                    quantityInput.value = quantity - 1;
                    updateTotal();
                }
            });

            plusBtn.addEventListener('click', function() {

                let quantity = parseInt(quantityInput.value);
                let max = parseInt(quantityInput.max);

                if (quantity < max) {
                    quantityInput.value = quantity + 1;
                    updateTotal();
                } else {
                    alert('在庫数を超えることはできません');
                }
            });
        });

    });
</script>

<body>

    <h1 class="title">カート</h1>

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

            <div class="stock-area">
                在庫数：{{ $product->stock }}
            </div>

            <div class="quantity-area">
                個数

                <button type="button" class="minus-btn">－</button>

                <input
                    type="number"
                    class="quantity-input"
                    value="{{ $product->quantity }}"
                    min="1"
                    max="{{ $product->stock }}">

                <button type="button" class="plus-btn">＋</button>
            </div>

            <div class="price-area" data-price="{{ $product->price }}">
                {{ number_format($product->price) }}円
            </div>

            <div class="subtotal-area">
                小計
                <span class="subtotal">
                    {{ number_format($product->price * $product->quantity) }}
                </span>円
            </div>

            <form action="/cart/delete" method="post">
                @csrf

                <input
                    type="hidden"
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
        合計 ¥<span id="total-price">{{ number_format($total) }}</span>
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