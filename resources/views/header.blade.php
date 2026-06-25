@php
// 1. Laravelの認証機能（Auth）からログイン状態とユーザー名を取得
$is_logged_in = Auth::check();
$user_name = $is_logged_in ? Auth::user()->name : '';

// 2. カートの数をデータベース（cart_itemsテーブル）から取得
$cart_count = 0;
// お気に入りの数をデータベース（favoritesテーブル）から取得
$favorite_count = 0;

if ($is_logged_in) {
// ログイン中のユーザーの、カート内商品の合計数量（quantityの合計値）を計算
$cart_count = \DB::table('cart_items')
->where('user_id', Auth::id())
->sum('quantity');

// ログイン中のユーザーのお気に入り登録総数をカウント
$favorite_count = \DB::table('favorites')
->where('user_id', Auth::id())
->count();
}

// 3. 検索フォーム用のカテゴリ一覧をデータベースから取得
$categories = \DB::table('categories')->get();

// 4. お気に入りアイコン用のリンクURLを動的に組み立て（現在の検索条件を引き継ぐ）
$favorite_params = request()->query();
$favorite_params['favorite'] = 1; // お気に入りフラグをONにする
$favorite_url = '/products?' . http_build_query($favorite_params);
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
</head>

<header class="navbar navbar-expand-lg navbar-light bg-light border-bottom sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold fs-3 text-primary" href="/products">
            <i class="fa-solid fa-shop me-2"></i>真理文庫
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#headerNavbar" aria-controls="headerNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="headerNavbar">
            <form class="d-flex mx-auto my-2 my-lg-0 w-50" action="/products" method="GET">
                @if(request('keyword'))
                <input type="hidden" name="keyword" value="{{ request('keyword') }}">
                @endif
                @if(request('category_id'))
                <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                @endif
                @if(request('min_price'))
                <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                @endif
                @if(request('max_price'))
                <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                @endif
                @if(request('sort'))
                <input type="hidden" name="sort" value="{{ request('sort') }}">
                @endif
                @if(request('favorite'))
                <input type="hidden" name="favorite" value="{{ request('favorite') }}">
                @endif

                <div class="input-group">
                    <input type="search" name="keyword" class="form-control" placeholder="商品名を入力..." aria-label="Search" value="{{ request('keyword') }}">

                    <select name="category_id" class="form-select header-category-select" onchange="this.form.submit()">
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

            <div class="d-flex align-items-center justify-content-end header-user-actions" style="min-width: 320px;">

                <div class="d-flex align-items-center gap-2 me-3">
                    @if ($is_logged_in)
                    <a href="/account/edit" class="header-icon-btn" title="アカウント設定">
                        <i class="fa-solid fa-user-gear fs-5"></i>
                    </a>
                    @endif

                    @if ($is_logged_in)
                    <a href="{{ $favorite_url }}" id="header-favorite-btn" data-current-count="{{ $favorite_count }}" class="header-icon-btn position-relative {{ request('favorite') ? 'active-filter' : '' }}" title="お気に入りで絞り込み">
                        <i class="fa-solid fa-heart fs-5 text-danger"></i>
                        <span id="header-favorite-badge" class="position-absolute badge rounded-pill bg-danger {{ $favorite_count == 0 ? 'd-none' : '' }}">
                            {{ $favorite_count }}
                        </span>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>