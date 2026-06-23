@include('header')
<link rel="stylesheet" href="{{ asset('css/lineup.css') }}">
<link rel="stylesheet" href="{{ asset('css/lineup_filter.css') }}">

<div class="container mt-4">
    <div class="sort-navigation-bar">
        <div class="sort-group">
            <label for="productSort" class="sort-label">並び替え:</label>
            <select id="productSort" class="sort-select" onchange="changeSort(this.value)">
                @if ($is_logged_in)
                <option value="recommend" {{ request('sort', 'recommend') == 'recommend' ? 'selected' : '' }}>おすすめ順</option>
                @endif

                <option value="bestseller" {{ request('sort', $is_logged_in ? '' : 'bestseller') == 'bestseller' ? 'selected' : '' }}>ベストセラー</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>価格：安い順</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>価格：高い順</option>
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>新着商品</option>
            </select>
        </div>
    </div>

    <h1 class="page-title">商品一覧</h1>
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

    function changeSort(sortValue) {
        const url = new URL(window.location.href);
        url.searchParams.set('sort', sortValue);
        window.location.href = url.toString(); // 選択された項目をURLにつけて再読込
    }
</script>

@include('footer')