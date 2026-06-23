<head>
    <meta charset="UTF-8">
    <title>アカウント設定</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/account_edit.css') }}">
</head>

<body>

    @include('header')

    <div class="account-container">
        <x-account-sidebar />

        <main class="account-main">
            <div class="card">
                <h2 class="card-title">アカウント情報の変更</h2>

                @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
                @endif

                <form action="#" method="POST" class="account-form">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="name" class="form-label">お名前</label>
                        <input type="text" id="name" name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name ?? '') }}" required>
                        @error('name')
                        <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">メールアドレス</label>
                        <input type="email" id="email" name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email ?? '') }}" required>
                        @error('email')
                        <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="current_password" class="form-label">現在のパスワード</label>
                        <input type="password" id="current_password" name="current_password"
                            class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                            required autocomplete="current-password">
                        @error('current_password', 'updatePassword')
                        <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">新しいパスワード</label>
                        <input type="password" id="password" name="password"
                            class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                            required autocomplete="new-password" placeholder="新しいパスワードを入力">
                        @error('password', 'updatePassword')
                        <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">新しいパスワード（確認用）</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="form-control" required autocomplete="new-password">
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">変更内容を保存する</button>
                        <a href="#" class="btn btn-secondary">キャンセル</a>
                    </div>
                </form>
            </div>
            </< /main>
    </div>

    @include('footer')

</body>