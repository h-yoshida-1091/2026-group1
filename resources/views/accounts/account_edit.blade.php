<head>
    <meta charset="UTF-8">
    <title>アカウント設定</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    @include('header')

    <div class="account-container">
        <aside class="account-sidebar">
            <nav class="sidebar-nav">
                <a href="#" class="nav-item">注文履歴</a>
                <a href="#" class="nav-item active">アカウント情報編集</a>
                <a href="#" class="nav-item">パスワード変更</a>
                <a href="#" class="nav-item">お届け先一覧</a>
                <a href="#" class="nav-item">ログアウト</a>
            </nav>
        </aside>

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

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">変更内容を保存する</button>
                        <a href="#" class="btn btn-secondary">キャンセル</a>
                    </div>
                </form>
            </div>
            </< /main>
    </div>

</body>