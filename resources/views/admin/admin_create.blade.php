<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>【管理画面】商品追加</title>
    <link rel="stylesheet" href="{{ asset('css/admin_create.css') }}">
</head>

<body>

@include('admin.admin_header')

<div class="admin-main-layout">

    @include('admin.admin_sidebar')

    <div class="admin-container" style="padding-top: 20px;">

        <h1 class="page-title">商品追加</h1>

        <div class="product-detail">

            <div class="product-image">
                <img src="" alt="プレビュー" id="preview" style="width:100%; display:none;">
            </div>

            <div class="product-info">

                <form action="/admin/products/create" method="post" enctype="multipart/form-data">
                    @csrf

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
                        <input type="text" name="image_url" id="image_url" value="{{ old('image_url') }}">
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

                    <div>
                        <label>商品名</label>
                        <input type="text" name="name" value="{{ old('name') }}" required>
                    </div>

                    <br>

                    <div>
                        <label>価格</label>
                        <input type="number" name="price" value="{{ old('price') }}" required>
                    </div>

                    <br>

                    <div>
                        <label>在庫数</label>
                        <input type="number" name="stock" value="{{ old('stock') }}" required>
                    </div>

                    <br>

                    <div class="description">
                        <h3>商品説明</h3>
                        <textarea name="description" required>{{ old('description') }}</textarea>
                    </div>

                    <br>

                    <div>
                        <label>カテゴリ</label>
                        <select name="category_id" required>
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <br>

                    <button type="submit" class="cart-btn">追加する</button>

                </form>

            </div>

        </div>

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
            fileInput.disabled = true;
        } else {
            urlDiv.style.display = 'none';
            urlInput.disabled = true;

            fileDiv.style.display = 'block';
            fileInput.disabled = false;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        toggleImageInput();

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

</body>

</html>