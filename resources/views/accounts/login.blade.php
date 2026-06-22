<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン - ECサイト</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
    <div class="login-container">
        <h1>ログイン</h1>

        @if (session('error_message'))
        <div class="alert alert-danger" style="color: red; margin-bottom: 15px;">
            {{ session('error_message') }}
        </div>
    @endif

    <form action="/login" method="post" class="login-form">
        @csrf <div class="form-group">
            <label for="email">メールアドレス</label>
            <input type="email" id="email" name="email" placeholder="example@email.com" required>
        </div>

            <div class="form-group">
                <label for="password">パスワード</label>
                <input type="password" id="password" name="password" placeholder="パスワードを入力" required>
            </div>

            <button type="submit" class="btn-login">ログイン</button>
        </form>

        <div class="login-footer">
            <a href="/account">新規登録はこちら</a>
        </div>
    </div>

</body>

</html>