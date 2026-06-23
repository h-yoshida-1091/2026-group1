@php
// 1. Laravelの認証機能（Auth）からログイン状態とユーザー名を取得
$is_logged_in = Auth::check();
$user_name = $is_logged_in ? Auth::user()->name : '';

// 3. 検索フォーム用のカテゴリ一覧をデータベースから取得
$categories = \DB::table('categories')->get();
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin_header.css') }}">
</head>

<header class="navbar navbar-expand-lg navbar-light bg-light border-bottom sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold fs-3 text-primary" href="/admin/products">
            <i class="fa-solid fa-shop me-2"></i>MyShop_Admin
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#headerNavbar" aria-controls="headerNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="headerNavbar">
            <form class="d-flex mx-auto my-2 my-lg-0 w-50" action="/admin/products" method="GET">
                <div class="input-group">
                    <input type="search" name="keyword" class="form-control" placeholder="商品名を入力..." aria-label="Search" value="{{ request('keyword') }}">

                    <select name="category_id" class="form-select header-category-select">
                        <option value="">すべての教え</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>

                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </form>

            <!-- 商品追加ボタン ↓ここに追加 -->
            <a href="/admin/products/create" class="btn btn-primary ms-2 text-nowrap">
                <i class="fa-solid fa-plus me-1"></i>商品追加
            </a>

            <div class="d-flex align-items-center justify-content-end header-user-actions" style="min-width: 300px;">

                <div class="d-flex align-items-center gap-2 text-nowrap">
                    @if ($is_logged_in)
                    <span class="navbar-text me-1">
                        ようこそ、<strong>{{ $user_name }}</strong> 様
                    </span>
                    <form action="/logout" method="POST" class="d-inline mb-0">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger header-action-btn">
                            ログアウト
                        </button>
                    </form>
                    @else
                    <a href="/login" class="btn btn-sm btn-primary header-action-btn">ログイン</a>
                    @endif
                </div>

            </div>
        </div>
    </div>
</header>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>