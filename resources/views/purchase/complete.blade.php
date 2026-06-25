<!DOCTYPE html>
<html lang="ja">

<head>
    <title>購入完了</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/complete.css') }}">
</head>

<body>

    <div class="complete-container">

        <h1 class="complete-title">購入が完了しました</h1>

        <p class="complete-message">
            ご購入ありがとうございました。<br>
            商品の発送準備を開始いたします。
        </p>

        <a href="/products" class="continue-btn">買い物を続ける</a>

        <div class="recommend-section">
            <h2>あなたへのおすすめ商品</h2>

            <div class="recommend-list">
                @forelse($recommendedProducts as $product)
                    <div class="recommend-card">
                        <div class="recommend-img-wrapper">
                            @if($product->image_url)
                                <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}">
                            @else
                                <img src="{{ asset('images/no-image.png') }}" alt="No Image">
                            @endif
                        </div>

                        <h3>{{ $product->name }}</h3>
                        <p class="price">¥{{ number_format($product->price) }}</p>

                        <a href="{{ url('/products/detail?id=' . $product->id) }}" class="view-btn">
                            商品を見る
                        </a>
                    </div>  
                @empty
                    <p class="no-recommend">おすすめ商品はありません。</p>
                @endforelse
            </div>
        </div>

    </div>

</body>

</html>