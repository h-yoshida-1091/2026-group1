@include('header')
<title>商品詳細</title>

<link rel="stylesheet" href="{{ asset('css/detail.css') }}">

<h1 class="page-title">商品詳細</h1>

<div class="product-detail">

    <div class="product-image">
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
    </div>

    <div class="product-info">

        <div class="product-header">
            <h2>{{ $product->name }}</h2>

            <button
                class="favorite-btn {{ $product->is_favorited ? 'favorited' : '' }}"
                data-product-id="{{ $product->id }}">
                {{ $product->is_favorited ? '♥' : '♡' }}
            </button>
        </div>

        <p class="price">
            ¥{{ number_format($product->price) }}
        </p>

        <div class="description">
            <h3>商品説明</h3>
            <p>{{ $product->description }}</p>
        </div>

        <div class="quantity-area" style="margin: 15px 0;">
            <label for="quantity-select" style="font-weight: bold; margin-right: 10px;">数量：</label>
            <select id="quantity-select" class="form-select" style="width: 100px; display: inline-block;">
                @for ($i = 1; $i <= min($product->stock, 10); $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
        </div>

        <div class="button-area">

            <form action="/cart/add" method="POST" id="cart-form">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" id="cart-quantity" value="1">

                <button type="submit" class="cart-btn">
                    カートに入れる
                </button>
            </form>

            <div class="sub-buttons">

                <form action="/purchase/now" method="POST" id="purchase-form">
                    @csrf

                    <input type="hidden"
                        name="products[0][id]"
                        value="{{ $product->id }}">

                    <input type="hidden"
                        name="products[0][quantity]"
                        id="purchase-quantity"
                        value="1">

                    <button type="submit" class="buy-now-btn">
                        今すぐ購入
                    </button>
                </form>

                <a href="/products" class="back-products-btn">
                    商品一覧へ戻る
                </a>

            </div>

        </div>

    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // --- 数量セレクトボックスの連動処理 ---
        const quantitySelect = document.getElementById('quantity-select');
        const cartQuantityInput = document.getElementById('cart-quantity');
        const purchaseQuantityInput = document.getElementById('purchase-quantity');

        if (quantitySelect) {
            quantitySelect.addEventListener('change', function() {
                const selectedValue = this.value;
                // ドロップダウンで選ばれた数値を、それぞれのフォームのhidden inputに同期させる
                if (cartQuantityInput) cartQuantityInput.value = selectedValue;
                if (purchaseQuantityInput) purchaseQuantityInput.value = selectedValue;
            });
        }

        // --- お気に入り機能の非同期通信 ---
        const favoriteButton = document.querySelector('.favorite-btn');

        if (!favoriteButton) return;

        favoriteButton.addEventListener('click', function() {

            const productId = this.dataset.productId;
            const isFavorited = this.classList.contains('favorited');

            const url = isFavorited ?
                '/products/unfavorite' :
                '/products/favorite';

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        product_id: productId
                    })
                })
                .then(response => {

                    if (response.status === 401) {
                        alert('お気に入り機能を利用するにはログインしてください。');
                        location.href = '/login';
                        return;
                    }

                    return response.json();
                })
                .then(data => {

                    if (!data || data.status !== 'success') return;

                    if (isFavorited) {
                        this.classList.remove('favorited');
                        this.textContent = '♡';
                    } else {
                        this.classList.add('favorited');
                        this.innerHTML = '❤';
                    }

                })
                .catch(error => {
                    console.error('Error:', error);
                });

        });

    });
</script>

@include('footer')