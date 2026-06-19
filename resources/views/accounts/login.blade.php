<div class="login-container">
    <h1>ログイン</h1>

    <form action="/login" method="post" class="login-form">
        <div class="form-group">
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