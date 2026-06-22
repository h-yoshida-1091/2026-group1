<header style="border-bottom: 1px solid #ccc; padding: 10px;">
    <form action="/products" method="GET" style="display: inline;">
        
        <input type="text" name="keyword" placeholder="商品名で検索..."value="{{ $keyword ?? '' }}">

        <select name="category_id">
            <option value="">すべてのカテゴリ</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ (isset($categoryId) && $categoryId == $category->id) ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <button type="submit">🔍 検索</button>
    </form>

    <a href="/cart" style="margin-left: 20px;">🛒 カート</a>
</header>