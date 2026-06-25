<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>【管理画面】ユーザー管理</title>
    <link rel="stylesheet" href="{{ asset('css/admin_user.css') }}">
    <script src="https://yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8" defer></script>
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
                        <th>名前</th>
                        <th>メールアドレス</th>
                        <th>郵便番号</th>
                        <th>住所</th>
                        <th>権限</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->postal_code ?? 'なし' }}</td>
                        <td>{{ $user->address }}</td>
                        <td>{{ $user->role === 'admin' ? '管理者' : '一般ユーザー' }}</td>
                        <td>
                            <form action="/admin/users/delete" method="post">
                                @csrf
                                <input type="hidden" name="id" value="{{ $user->id }}">
                                <button type="submit" class="delete-btn" onclick="return confirm('{{ $user->name }}を削除しますか？')">削除</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- ユーザー追加フォーム -->
        <div class="user-form">
            <h2>追加</h2>

            <form action="/admin/users/create" method="post" class="h-adr">
                @csrf
                <span class="p-country-name" style="display:none;">Japan</span>

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
                    <input type="text" name="postal_code" value="{{ old('postal_code') }}" class="p-postal-code">
                    @error('postal_code')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <br>

                <div>
                    <label>住所</label>
                    <input type="text" name="address" value="{{ old('address') }}" class="p-region p-locality p-street-address">
                    @error('address')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <br>

                <div>
                    <label>権限</label>
                    <div style="display:flex; gap:20px; margin-top:5px;">
                        <label style="font-weight:normal;">
                            <input type="radio" name="role" value="user" {{ old('role', 'user') === 'user' ? 'checked' : '' }}> 一般ユーザー
                        </label>
                        <label style="font-weight:normal;">
                            <input type="radio" name="role" value="admin" {{ old('role') === 'admin' ? 'checked' : '' }}> 管理者
                        </label>
                    </div>
                    @error('role')
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