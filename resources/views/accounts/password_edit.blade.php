<head>
    <meta charset="UTF-8">
    <title>パスワード変更</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/account_edit.css') }}">
</head>

<body>
    <div class="account-container">
    <x-account-sidebar />

    <div class="card mt-6">
        <h2 class="card-title">パスワードの変更</h2>

        <form action="{{ route('password.update') }}" method="POST" class="account-form">
            @csrf
            @method('PUT')

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
                <button type="submit" class="btn btn-primary">パスワードを更新する</button>
            </div>
        </form>
    </div>

    @include('footer')

<body>