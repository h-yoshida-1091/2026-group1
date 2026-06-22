@include('layouts.header')
<!--この部分は削除して商品詳細用CSSに書き込んでください-->
<style>
.favorite-btn {
    background: none;
    border: none;
    font-size: 28px; /* 詳細画面なので少し大きめに */
    cursor: pointer;
    color: #ccc; /* 通常時はグレー */
    outline: none;
    transition: color 0.2s;
    padding: 0;
    line-height: 1;
}
/* お気に入り時の赤 */
.favorite-btn.favorited {
    color: #E60012; 
}
</style>
<a href="/products">一覧へ戻る(ヘッダーのサイトロゴが実装次第消す)</a>

<h1>商品詳細</h1>

<div style="display: flex; align-items: center; gap: 15px;">
    <h2>{{ $product->name }}</h2>

    <button class="favorite-btn {{ $product->is_favorited ? 'favorited' : '' }}"
        data-product-id="{{ $product->id }}">
        {{ $product->is_favorited ? '♥' : '♡' }}
    </button>
</div>
<img src="{{ $product->image_url }}" alt="商品画像">
<p>説明: {{ $product->description }}</p>
<p>価格: {{ $product->price }}円</p>
<p>在庫: {{ $product->stock }}</p>

<!-- カートボタン -->
<form action="/cart/add" method="post">
    @csrf
    <input type="hidden" name="product_id" value="{{ $product->id }}"> <input type="hidden" name="quantity" value="1">
    <button type="submit">カートに入れる</button>
</form>
<a href="/purchase/direct?id={{ $product->id }}">
    今すぐ購入
</a>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const favoriteButton = document.querySelector('.favorite-btn');

    // 詳細画面にボタンが存在する場合のみ処理を実行（エラー防止）
    if (favoriteButton) {
        favoriteButton.addEventListener('click', function () {
            const productId = this.getAttribute('data-product-id');
            const isFavorited = this.classList.contains('favorited');
            
            const url = isFavorited ? '/products/unfavorite' : '/products/favorite';

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(response => {
                if (response.status === 401) {
                    alert('お気に入り機能を利用するにはログインしてください。');
                    window.location.href = '/login';
                    return;
                }
                return response.json();
            })
            .then(data => {
                if (data && data.status === 'success') {
                    if (isFavorited) {
                        this.classList.remove('favorited');
                        this.innerText = '♡';
                    } else {
                        this.classList.add('favorited');
                        this.innerText = '♥';
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }
});
</script>