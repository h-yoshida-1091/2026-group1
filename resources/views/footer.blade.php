<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/hooter.css') }}">
</head>

<footer class="footer">
    <div class="footer-container">
        <div class="footer-sections">
            <div class="footer-column">
                <h3>【ショッピング】</h3>
                <ul>
                    <li>
                        <a href="/products" class="hover:underline">・商品一覧から探す</a>
                    </li>
                    <li>
                        <span>・カテゴリから探す</span>
                        <ul>
                            @foreach(\App\Models\Category::all() as $category)
                            <li>
                                <a href="{{ url('/products?category_id=' . $category->id) }}" class="hover:underline">
                                    ・{{ $category->name }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
            </div>

            <div class="footer-column">
                <h3>【サポート】</h3>
                <ul>
                    <li><a href="#">・ご利用ガイド</a></li>
                    <li><a href="#">・よくあるご質問</a></li>
                </ul>
            </div>

            <div class="footer-column">
                <h3>【会社情報】</h3>
                <ul>
                    <li><a href="#">・運営会社（2026-TBC-group1）</a></li>
                    <li><a href="#">— プライバシーポリシー</a></li>
                </ul>
            </div>
        </div>

        <hr class="footer-divider">

        <div class="footer-copyright">
            &copy; 2026-group1@project.ec
        </div>
    </div>
</footer>