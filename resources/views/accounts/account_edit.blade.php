<head>
    <meta charset="UTF-8">
    <title>アカウント設定</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    @include('header') 

    <div class="container my-5" style="max-width: 600px;">
        <h2 class="mb-4">アカウント情報の編集</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="/account/edit" method="POST">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">ユーザー名</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">メールアドレス</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label for="address" class="form-label">お届け先住所</label>
                <textarea name="address" id="address" class="form-control" rows="3" placeholder="都道府県、市区町村、番地、建物名などを入力してください" required>{{ old('address', $user->address) }}</textarea>
                @error('address') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">変更を保存する</button>
        </form>
    </div>

</body>