@include('admin.admin_header')

<link rel="stylesheet" href="{{ asset('css/admin_edit.css') }}">

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

            <div id="url_input">
                <label>画像URL</label>
                <input type="text" name="image_url" id="image_url" value="{{ old('image_url', $product->image_url) }}">
                @error('image_url')
                    <span class="error-message" style="color: red; display: block; margin-top: 5px;">{{ $message }}</span>
                @enderror
            </div>

            <div id="file_input" style="display:none;">
                <label>画像ファイル</label>
                <input type="file" name="image_file" id="image_file" accept="image/*">
                @error('image_file')
                    <span class="error-message" style="color: red; display: block; margin-top: 5px;">{{ $message }}</span>
                @enderror
            </div>

            <br>

            <div class="product-header">
                <div>
                    <label>商品名</label>
                    <input type="text" name="name" value="{{ $product->name }}" required>
                </div>
            </div>

            <br>

            <div>
                <label>価格</label>
                <input type="number" name="price" value="{{ $product->price }}" required>
            </div>

            <br>

            <div>
                <label>在庫数</label>
                <input type="number" name="stock" value="{{ $product->stock }}" required>
            </div>

            <br>

            <div class="description">
                <h3>商品説明</h3>
                <textarea name="description" required>{{ $product->description }}</textarea>
            </div>

            <br>

            <div>
                <label>カテゴリ</label>
                <select name="category_id" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ $product->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <br>

            <button type="submit" class="cart-btn">更新する</button>

        </form>

    </div>

</div>

<script>
    function toggleImageInput() {
        const type = document.querySelector('input[name="image_type"]:checked').value;
        const urlDiv = document.getElementById('url_input');
        const fileDiv = document.getElementById('file_input');
        const urlInput = document.getElementById('image_url');
        const fileInput = document.getElementById('image_file');

        if (type === 'url') {
            urlDiv.style.display = 'block';
            urlInput.disabled = false;

            fileDiv.style.display = 'none';
            fileInput.disabled = true; // 選択されていない方は無効化してリクエストから除外
        } else {
            urlDiv.style.display = 'none';
            urlInput.disabled = true;  // 選択されていない方は無効化してリクエストから除外

            fileDiv.style.display = 'block';
            fileInput.disabled = false;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // ページ読み込み時に初期状態を正しくセット
        toggleImageInput();

        // ファイル選択時にプレビュー表示
        document.getElementById('image_file').addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('preview');
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });

        // URL入力時にプレビュー表示
        document.getElementById('image_url').addEventListener('input', function() {
            const preview = document.getElementById('preview');
            if (this.value) {
                preview.src = this.value;
                preview.style.display = 'block';
            } else {
                preview.style.display = 'none';
            }
        });
    });
</script>