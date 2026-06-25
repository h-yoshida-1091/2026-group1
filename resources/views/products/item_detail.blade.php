@include('header')
<title>商品詳細</title>

<link rel="stylesheet" href="{{ asset('css/detail.css') }}">

<h1 class="page-title">商品詳細</h1>

@if (session('success'))
<div class="alert-success" style="color: green; background-color: #f0fff4; border: 1px solid #c6f6d5; padding: 12px; margin: 10px auto; max-width: 1200px; border-radius: 4px; font-weight: bold;">
    {{ session('success') }}
</div>
@endif
@if (session('error'))
<div class="alert-danger" style="color: red; background-color: #fff5f5; border: 1px solid #fed7d7; padding: 12px; margin: 10px auto; max-width: 1200px; border-radius: 4px; font-weight: bold;">
    {{ session('error') }}
</div>
@endif

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

        <div class="average-rating-area">
            <div class="star-rating" title="評価: {{ number_format($averageRating, 1) }}">
                <span>★★★★★</span> <span class="star-filled" style="width: calc({{ $averageRating }} / 5 * 100%);">★★★★★</span>
            </div>
            <span class="rating-number">{{ number_format($averageRating, 1) }}</span>
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

                <button type="submit" class="cart-btn" {{ $product->stock === 0 ? 'disabled' : '' }}>
                    {{ $product->stock === 0 ? '売り切れ' : 'カートに入れる' }}
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

                    <button type="submit" class="buy-now-btn" {{ $product->stock === 0 ? 'disabled' : '' }}>
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

<div class="reviews-section" style="max-width: 1200px; margin: 40px auto; padding: 0 20px;">
    <h2 style="border-bottom: 2px solid #2c2a29; padding-bottom: 10px; margin-bottom: 20px;">カスタマーレビュー</h2>

    <div class="reviews-list">
        @forelse($product->reviews as $review)
        <div class="review-item">
            <div class="review-header">
                <div class="star-rating" title="評価: {{ number_format($review->rating, 1) }}">
                    <span>★★★★★</span>
                    <span class="star-filled" style="width: calc({{ $review->rating }} / 5 * 100%);">★★★★★</span>
                </div>
                <span class="review-title">{{ $review->title ?? '無題' }}</span>
            </div>

            <div class="review-meta">
                投稿者: {{ $review->user->name }} | 投稿日時: {{ $review->created_at->format('Y/m/d H:i') }}
            </div>

            <p class="review-comment">{{ $review->comment }}</p>

            @if(Auth::check() && $review->user_id === Auth::id())
            <form action="/reviews/{{ $review->id }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete-review-btn" onclick="return confirm('このレビューを削除しますか？')">
                    削除する
                </button>
            </form>
            @endif
        </div>
        @empty
        <p style="color: #666; font-style: italic; padding: 20px 0;">この商品へのレビューはまだありません。</p>
        @endforelse
    </div>

    <div class="review-form-area" style="background-color: #fcfbfa; border: 1px solid #e5e0da; padding: 25px; border-radius: 4px;">
        <h3 style="margin-top: 0; margin-bottom: 20px;">この商品のレビューを書く</h3>

        @auth
        <form action="/reviews" method="POST">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="rating" style="display: block; font-weight: bold; margin-bottom: 5px;">評価スコア (0.0 〜 5.0)</label>
                <select name="rating" id="rating" required style="padding: 8px; border: 1px solid #ccc; border-radius: 4px; width: 120px;">
                    @for($i = 50; $i >= 0; $i -= 5)
                    <option value="{{ $i / 10 }}" {{ old('rating', '5.0') == ($i / 10) ? 'selected' : '' }}>
                        {{ number_format($i / 10, 1) }}
                    </option>
                    @endfor
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="title" style="display: block; font-weight: bold; margin-bottom: 5px;">タイトル（任意）</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" placeholder="例: 最高の一冊です" style="width: 100%; max-width: 500px; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label for="comment" style="display: block; font-weight: bold; margin-bottom: 5px;">レビュー内容（必須）</label>
                <textarea name="comment" id="comment" rows="5" required placeholder="商品の感想や、どのような点がお気に入りかをご記入ください。" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; resize: vertical;">{{ old('comment') }}</textarea>
            </div>

            <button type="submit" class="submit-review-btn" style="background-color: #2c2a29; color: #fff; padding: 10px 24px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">
                レビューを投稿する
            </button>
        </form>
        @else
        <p style="margin: 0; color: #666;">
            レビューを投稿するには、<a href="/login" style="color: #c5a880; font-weight: bold; text-decoration: none;">ログイン</a>が必要です。
        </p>
        @endauth
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