@include('layouts.header')
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

    /* お気に入り時の赤 */
    .favorite-btn.favorited {
        color: #E60012;
    }
</style>
<h1>商品一覧</h1>
@foreach ($products as $product)
<div style="border:1px solid #000; margin-bottom:20px; padding:10px; display:flex;">

    <!-- 左：画像 -->
    <div style="width:300px;">
        <img src="{{ $product->image_url }}" alt="商品画像" style="width:100%;">
    </div>

    <!-- 右：商品情報 -->
    <div style="margin-left:20px;">
        <h3>{{ $product->name }}</h3>

        <p>在庫数：{{ $product->stock }}</p>
        <p>価格：{{ $product->price }}円</p>

        <br>


        <!-- カートボタン -->
        <form action="/cart/add" method="post">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="quantity" value="1">
            <button type="submit">カートに入れる</button>
        </form>

        <br><br>
        <!-- 詳細リンク -->
        <a href="/products/detail?id={{ $product->id }}">詳細へ</a>

    </div>

    <div class="product-item">
        <!-- お気に入りボタン -->
        <button class="favorite-btn {{ $product->is_favorited ? 'favorited' : '' }}"
            data-product-id="{{ $product->id }}">
            {{ $product->is_favorited ? '♥' : '♡' }}
        </button>
    </div>

</div>
@endforeach

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 画面内のすべてのお気に入りボタンを取得
        const favoriteButtons = document.querySelectorAll('.favorite-btn');

        favoriteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                const isFavorited = this.classList.contains('favorited');

                // 現在の状態に応じて、叩くURL（登録か削除か）を切り替える
                const url = isFavorited ? '/products/unfavorite' : '/products/favorite';

                // 非同期通信（Fetch API）の実行
                fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            // Laravelのセキュリティ（CSRF）対策トークンをヘッダーにセット
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            product_id: productId
                        })
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
                            // 通信成功時、画面をリロードせずに見た目（マークと色）を切り替える
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
        });
    });
</script>