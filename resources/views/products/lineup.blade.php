@include('header')
<!--この部分は削除して商品一覧用CSSに書き込んでください-->
<style>
    .favorite-btn {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #ccc;
        /* 通常時はグレー */
        outline: none;
        transition: color 0.2s;
    }
    
</style>

<link rel="stylesheet" href="{{ asset('css/lineup.css') }}">


<h1 class="page-title">商品一覧</h1>

<div class="page-header">
    <a href="/cart" class="cart-link"> 🛒 カートを見る </a>
</div>

<div class="product-list">

    @foreach ($products as $product)

    <div class="product-card">

        <!-- お気に入り -->
        <div class="favorite-area">
            <button
                class="favorite-btn {{ $product->is_favorited ? 'favorited' : '' }}"
                data-product-id="{{ $product->id }}">
                {{ $product->is_favorited ? '♥' : '♡' }}
            </button>
        </div>

        <!-- 商品画像 -->
        <div class="product-image">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
        </div>

        <!-- 商品情報 -->
        <div class="product-info">

            <h3>{{ $product->name }}</h3>

            <p>在庫数：{{ $product->stock }}</p>

            <p class="price">
                ¥{{ number_format($product->price) }}
            </p>

            <div class="product-actions">

                <!-- カート -->
                <form action="/cart/add" method="POST">
                    @csrf

                    <input type="hidden"
                        name="product_id"
                        value="{{ $product->id }}">

                    <input type="hidden"
                        name="quantity"
                        value="1">

                    <button type="submit" class="cart-btn">
                        カートに入れる
                    </button>
                </form>

                <!-- 詳細 -->
                <a href="/products/detail?id={{ $product->id }}"
                    class="detail-link">
                    詳細を見る
                </a>

            </div>

        </div>

    </div>

    @endforeach

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const favoriteButtons =
            document.querySelectorAll('.favorite-btn');

        favoriteButtons.forEach(button => {

            button.addEventListener('click', function() {

                const productId =
                    this.dataset.productId;

                const isFavorited =
                    this.classList.contains('favorited');

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
                            alert('ログインしてください');
                            location.href = '/login';
                            return;
                        }

                        return response.json();
                    })
                    .then(data => {

                        if (!data ||
                            data.status !== 'success') {
                            return;
                        }

                        if (isFavorited) {

                            this.classList.remove('favorited');
                            this.textContent = '♡';

                        } else {

                            this.classList.add('favorited');
                            this.textContent = '♥';
                        }
                    })
                    .catch(error => console.error(error));

            });

        });

    });
</script>

@include('footer')