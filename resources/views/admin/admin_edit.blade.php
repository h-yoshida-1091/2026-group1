@include('header')

<link rel="stylesheet" href="{{ asset('css/detail.css') }}">

<a href="/admin/products" class="back-link">一覧へ戻る</a>

<h1 class="page-title">商品編集</h1>

<div class="product-detail">

    <!-- 商品画像 -->
    <div class="product-image">
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" id="preview">
    </div>

    <!-- 商品情報 -->
    <div class="product-info">

        <form action="/admin/products/edit/{{ $product->id }}" method="post" enctype="multipart/form-data">
            @csrf

            <!-- 画像選択 -->
            <div>
                <label>
                    <input type="radio" name="image_type" value="url" checked onclick="toggleImageInput()"> URLから入力
                </label>
                <label>
                    <input type="radio" name="image_type" value="file" onclick="toggleImageInput()"> ファイルから選択
                </label>
            </div>

            <br>

            <!-- URL入力 -->
            <div id="url_input">
                <label>画像URL</label>
                <input type="text" name="image_url" value="{{ $product->image_url }}">
            </div>

            <!-- ファイル選択 -->
            <div id="file_input" style="display:none;">
                <label>画像ファイル</label>
                <input type="file" name="image_file" accept="image/*">
            </div>

            <br>

            <div class="product-header">
                <div>
                    <label>商品名</label>
                    <input type="text" name="name" value="{{ $product->name }}">
                </div>
            </div>

            <br>

            <div>
                <label>価格</label>
                <input type="number" name="price" value="{{ $product->price }}">
            </div>

            <br>

            <div>
                <label>在庫数</label>
                <input type="number" name="stock" value="{{ $product->stock }}">
            </div>

            <br>

            <div class="description">
                <h3>商品説明</h3>
                <textarea name="description">{{ $product->description }}</textarea>
            </div>

            <br>

            <div>
                <label>カテゴリ</label>
                <select name="category_id">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ $product->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <br>

            <button type="submit">更新する</button>

        </form>

    </div>

</div>

<script>
function toggleImageInput() {
    const type = document.querySelector('input[name="image_type"]:checked').value;
    document.getElementById('url_input').style.display = type === 'url' ? 'block' : 'none';
    document.getElementById('file_input').style.display = type === 'file' ? 'block' : 'none';
}
</script>