<head>
    <title>商品一覧</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

@include('header')
<link rel="stylesheet" href="{{ asset('css/lineup.css') }}">

<div class="sort-navigation-bar">
    <button type="button" class="mobile-sidebar-toggle" onclick="toggleMobileSidebar()" title="絞り込み条件を開く">
        <i class="fa-solid fa-sliders"></i>
    </button>
    @if($categoryName || request('keyword') || request('min_price') || request('max_price')|| request('favorite'))
    <div class="filter-tags-group">
        <span class="filter-title">絞り込み条件:</span>

        @if(request('favorite'))
        @php
            // お気に入り解除時のURL用（favoriteパラメータだけを除く）
            $clear_favorite_params = request()->query();
            unset($clear_favorite_params['favorite']);
            $clear_favorite_url = '/products?' . http_build_query($clear_favorite_params);
        @endphp
        <span class="filter-tag border-danger text-danger fw-bold">
            <i class="fa-solid fa-heart me-1"></i> お気に入り
            <a href="{{ $clear_favorite_url }}" class="remove-tag-btn" style="color: #e74c3c; text-decoration: none;">×</a>
        </span>
        @endif

        {{-- キーワード検索のタグ --}}
        @if(request('keyword'))
        <span class="filter-tag">
            キーワード: 「{{ request('keyword') }}」
            <button type="button" class="remove-tag-btn" onclick="removeParam('keyword')">×</button>
        </span>
        @endif

        {{-- カテゴリ絞り込みのタグ --}}
        @if($categoryName)
        <span class="filter-tag">
            カテゴリ: 「{{ $categoryName }}」
            <button type="button" class="remove-tag-btn" onclick="removeParam('category_id')">×</button>
        </span>
        @endif

        {{-- 価格帯絞り込みのタグ --}}
        @if(request('min_price') || request('max_price'))
        <span class="filter-tag">
            価格: ¥{{ number_format(request('min_price', $floorMin)) }} 〜 @if(request('max_price') && request('max_price') < $ceilMax) ¥{{ number_format(request('max_price')) }} @else 上限なし @endif
                <button type="button" class="remove-tag-btn" onclick="removeParam('min_price', 'max_price')">×</button>
        </span>
        @endif

        {{-- すべて解除ボタン --}}
        <button type="button" class="clear-all-btn" onclick="clearAllFilters()">× すべて解除</button>
    </div>
    @endif

    <div class="sort-group">
        <label for="productSort" class="sort-label">並び替え:</label>
        <select id="productSort" class="sort-select" onchange="changeSort(this.value)">
            @if ($is_logged_in)
            <option value="recommend" {{ request('sort', 'recommend') == 'recommend' ? 'selected' : '' }}>おすすめ順</option>
            @endif

            <option value="bestseller" {{ request('sort', $is_logged_in ? '' : 'bestseller') == 'bestseller' ? 'selected' : '' }}>ベストセラー</option>
            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>価格：安い順</option>
            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>価格：高い順</option>
            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>新着商品</option>
        </select>
    </div>
</div>

<div class="main-layout-fluid">

    <aside class="sidebar">
        <div class="sidebar-widget">
            <button type="button" class="sidebar-close-btn" onclick="toggleMobileSidebar()">×</button>
            <h2 class="widget-title">価格</h2>

            <div class="slider-text-range">
                <span id="labelMinPrice">¥{{ number_format($minPriceParam ?? $floorMin) }}</span> 〜
                <span id="labelMaxPrice">¥{{ number_format($maxPriceParam ?? $ceilMax) }}</span>
            </div>

            <div class="double-slider-container">
                <div class="slider-track"></div>
                <input type="range" id="inputMinPrice" min="{{ $floorMin }}" max="{{ $ceilMax }}" step="100" value="{{ $minPriceParam ?? $floorMin }}" oninput="updateSlider()">
                <input type="range" id="inputMaxPrice" min="{{ $floorMin }}" max="{{ $ceilMax }}" step="100" value="{{ $maxPriceParam ?? $ceilMax }}" oninput="updateSlider()">
            </div>

            <div class="slider-action-row">
                <button type="button" class="price-apply-btn" onclick="applyPriceFilter()">価格を適用</button>
            </div>

            <ul class="price-range-list">
                @foreach($priceRanges as $range)
                <li>
                    <a href="javascript:void(0)"
                        onclick="clickPriceRange({{ $range['min'] }}, {{ $range['max'] }})"
                        class="price-range-link {{ (request('min_price') == $range['min'] && request('max_price') == $range['max']) ? 'active' : '' }}">
                        {{ $range['label'] }}
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    </aside>

    <div class="content-products">
        <h1 class="page-title-left">商品一覧</h1>

        <div class="product-list">
            @foreach ($products as $product)
            <div class="product-card">
                <div class="favorite-area">
                    <button class="favorite-btn {{ $product->is_favorited ? 'favorited' : '' }}" data-product-id="{{ $product->id }}">
                        {{ $product->is_favorited ? '♥' : '♡' }}
                    </button>
                </div>

                <div class="product-image">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                </div>

                <div class="product-info">
                    <h3>{{ $product->name }}</h3>
                    <p>在庫数：{{ $product->stock }}</p>
                    <p class="price">¥{{ number_format($product->price) }}</p>

                    <div class="product-actions">
                        <form action="/cart/add" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="cart-btn">カートに入れる</button>
                        </form>
                        <a href="/products/detail?id={{ $product->id }}" class="detail-link">詳細を見る</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>

<script src="{{ asset('js/lineup.js') }}"></script>
@include('footer')