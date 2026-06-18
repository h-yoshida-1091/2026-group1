<h1>商品詳細</h1>

<h2>{{ $product->name }}</h2>

<p>説明: {{ $product->description }}</p>
<p>価格: {{ $product->price }}円</p>
<p>在庫: {{ $product->stock }}</p>

<a href="/products">一覧へ戻る</a>