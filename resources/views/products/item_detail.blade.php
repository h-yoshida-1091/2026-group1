@include('header')

<link rel="stylesheet" href="{{ asset('css/detail.css') }}">

<a href="/products" class="back-link">一覧へ戻る</a>

<h1 class="page-title">商品詳細</h1>

<div class="product-detail">

    <!-- 商品画像 -->
    <div class="product-image">
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
    </div>

    <!-- 商品情報 -->
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

        <p class="stock">
            在庫：{{ $product->stock }}
        </p>

        <div class="description">
            <h3>商品説明</h3>
            <p>{{ $product->description }}</p>
        </div>

        <div class="button-area">

            <!-- カートに入れる -->
            <form action="/cart/add" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" value="1">

                <button type="submit" class="cart-btn">
                    カートに入れる
                </button>
            </form>

            <div class="sub-buttons">

                <!-- 今すぐ購入 -->
                <form action="/purchase/now" method="POST">
                    @csrf

                    <input type="hidden"
                        name="products[0][id]"
                        value="{{ $product->id }}">

                    <input type="hidden"
                        name="products[0][quantity]"
                        value="1">

                    <button type="submit" class="buy-now-btn">
                        今すぐ購入
                    </button>
                </form>

                <!-- 商品一覧へ戻る -->
                <a href="/products" class="back-products-btn">
                    商品一覧へ戻る
                </a>

            </div>

        </div>

    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

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