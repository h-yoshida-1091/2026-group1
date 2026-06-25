<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>【管理画面】ユーザー管理</title>
    <link rel="stylesheet" href="{{ asset('css/admin_user.css') }}">
</head>

<body>

    @include('admin.admin_header')

    @include('admin.admin_sidebar')

    <h1 class="page-title">ユーザー管理</h1>

    <div class="user-container">

        <!-- ユーザー一覧 -->
        <div class="user-list">
            <h2>一覧</h2>

            <table class="user-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>名前</th>
                        <th>メールアドレス</th>
                        <th>郵便番号</th>
                        <th>住所</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->postal_code ?? 'なし' }}</td>
                        <td>{{ $user->address }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- ユーザー追加フォーム -->
        <div class="user-form">
            <h2>追加</h2>

            <form action="/admin/users/create" method="post">
                @csrf

                <div>
                    <label>名前</label>
                    <input type="text" name="name" value="{{ old('name') }}">
                    @error('name')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <br>

                <div>
                    <label>メールアドレス</label>
                    <input type="email" name="email" value="{{ old('email') }}">
                    @error('email')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <br>

                <div>
                    <label>パスワード</label>
                    <input type="password" name="password">
                    @error('password')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <br>

                <div>
                    <label>郵便番号（任意）</label>
                    <input type="text" name="postal_code" value="{{ old('postal_code') }}">
                    @error('postal_code')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <br>

                <div>
                    <label>住所</label>
                    <input type="text" name="address" value="{{ old('address') }}">
                    @error('address')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <br>

                <button type="submit" class="cart-btn">追加する</button>

            </form>
        </div>

    </div>
</body>

</html>