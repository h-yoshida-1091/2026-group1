@include('admin.admin_header')
<link rel="stylesheet" href="{{ asset('css/admin_lineup.css') }}">

<div class="admin-main-layout">

    @include('admin.admin_sidebar')

    <div class="admin-container" style="padding-top: 20px;">

        <h1>管理画面 - 商品一覧</h1>

        <div class="product-list">

            @foreach ($products as $product)

            <div class="product-card">

                <div class="product-image">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                </div>

                <div class="product-info">

                    <h3>{{ $product->name }}</h3>

                    <p>在庫数：{{ $product->stock }}</p>

                    <p class="price">
                        ¥{{ number_format($product->price) }}
                    </p>

                    <div class="product-actions">

                        <form action="/admin/products/edit/{{ $product->id }}" method="get" style="display:inline;">
                            @csrf
                            <button type="submit" class="edit-btn">編集</button>
                        </form>

                        <form action="/admin/products/delete" method="post" style="display:inline;">
                            @csrf
                            <input type="hidden" name="id" value="{{ $product->id }}">
                            <button type="submit" class="delete-btn" onclick="return confirm('本当に削除しますか？')">削除</button>
                        </form>
                    </div>
                </div>

            </div>

            @endforeach

        </div>

    </div>

</div>