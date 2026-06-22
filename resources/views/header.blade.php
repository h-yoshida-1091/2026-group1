@php
// 1. Laravelの認証機能（Auth）からログイン状態とユーザー名を取得
$is_logged_in = Auth::check();
$user_name = $is_logged_in ? Auth::user()->name : '';

// 2. カートの数をデータベース（cart_itemsテーブル）から取得
$cart_count = 0;
if ($is_logged_in) {
// ログイン中のユーザーの、カート内商品の合計数量（quantityの合計値）を計算
$cart_count = \DB::table('cart_items')
->where('user_id', Auth::id())
->sum('quantity');
}

// 3. 検索フォーム用のカテゴリ一覧をデータベースから取得（追加）
// ※テーブル名が 'categories' であると仮定しています
$categories = \DB::table('categories')->get();
@endphp

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">

    <style>
        /* アカウント設定・カートボタン共通の丸型スタイル（以前の .btn-outline-dark を拡張・微調整） */
        .header-icon-btn {
            border: none;
            background-color: #f8f9fa;
            color: #333333;
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50% !important;
            transition: all 0.2s ease;
            text-decoration: none;
            /* リンクの下線を消す */
        }

        .header-icon-btn:hover {
            background-color: #e9ecef;
            color: #0d6efd;
            transform: scale(1.05);
        }
    </style>
</head>

<header class="navbar navbar-expand-lg navbar-light bg-light border-bottom sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold fs-3 text-primary" href="/products">
            <i class="fa-solid fa-shop me-2"></i>MyShop
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#headerNavbar" aria-controls="headerNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="headerNavbar">
            <form class="d-flex mx-auto my-2 my-lg-0 w-50" action="/products" method="GET">
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
                        <i class="fa-solid fa-magnifying-glass"></i> </button>
                </div>
            </form>

            <div class="d-flex align-items-center justify-content-end" style="min-width: 300px;">

                <div class="d-flex align-items-center gap-2 me-3">
                    @if ($is_logged_in)
                    <a href="/account/edit" class="header-icon-btn" title="アカウント設定">
                        <i class="fa-solid fa-user-gear fs-5"></i>
                    </a>
                    @endif

                    <a href="/cart" class="header-icon-btn position-relative" title="カートを見る">
                        <i class="fa-solid fa-cart-shopping fs-5"></i>
                        @if ($cart_count > 0)
                        <span class="position-absolute badge rounded-pill bg-danger">
                            {{ $cart_count }}
                        </span>
                        @endif
                    </a>
                </div>

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