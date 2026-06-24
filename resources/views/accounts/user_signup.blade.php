<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規登録はこちら - ECサイト</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">

    <script src="https://yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8" defer></script>
</head>

<body>
    <div class="register-container">
        <h1>新規会員登録</h1>

        @if ($errors->any())
            <div class="alert alert-danger" style="color: red; margin-bottom: 15px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
            
        <form action="/account" method="post" class="register-form h-adr">

            @csrf

            <span class="p-country-name" style="display:none;">Japan</span>

            <div class="form-group">
                <label for="name">お名前</label>
                <input type="text" id="name" name="name" placeholder="山田 太郎" required>
            </div>

            <div class="form-group">
                <label for="email">メールアドレス</label>
                <input type="email" id="email" name="email" placeholder="example@email.com" required>
            </div>

            <div class="form-group">
                <label for="password">パスワード</label>
                <input type="password" id="password" name="password" placeholder="パスワードを入力" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">確認用パスワード</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="パスワードを入力" required>
            </div>

            <div class="form-group">
                <label for="postal_code">郵便番号</label>
                <div class="postal-code-container" style="display: flex; gap: 10px;">
                    <input type="text" id="postal_code" name="postal_code" class="p-postal-code" placeholder="123-4567" style="flex: 1;">
                </div>
            </div>

            <div class="form-group">
                <label for="address">お届け先住所</label>
                <textarea id="address" name="address" class="p-region p-locality p-street-address" rows="3" placeholder="東京都〇〇区1-2-3" required></textarea>
            </div>

            <button type="submit" class="btn-register">登録する</button>
        </form>

        <div class="register-footer">
            <p class="mb-0">既にアカウントをお持ちですか？</p>
            <a href="/login">ログインはこちら</a>
        </div>

    </div>
</body>

</html>