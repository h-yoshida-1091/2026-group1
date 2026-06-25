<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>【管理画面】カテゴリー管理</title>
</head>

<body>


    @include('admin.admin_header')

    <style>
        /* 全体レイアウト（サイドバー共通設定） */
        .admin-main-layout {
            display: flex;
            position: relative;
            width: 100%;
            min-height: 100vh;
        }

        .admin-container {
            flex: 1;
            padding: 20px;
            box-sizing: border-box;
        }

        .page-title {
            margin-bottom: 25px;
            font-size: 1.8rem;
        }

        /* カテゴリー画面用のレイアウト（左右2カラム） */
        .category-management-wrap {
            display: flex;
            gap: 30px;
            align-items: flex-start;
        }

        /* 左側：追加フォーム */
        .category-create-box {
            width: 320px;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .category-create-box h3 {
            margin-top: 0;
            margin-bottom: 15px;
            font-size: 1.1rem;
            border-bottom: 2px solid #333;
            padding-bottom: 5px;
        }

        /* 右側：一覧テーブル */
        .category-list-box {
            flex: 1;
        }

        .category-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
        }

        .category-table th,
        .category-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        .category-table th {
            background-color: #f1f1f1;
            font-weight: bold;
        }

        /* 各種入力欄・ボタン */
        .input-text {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.2s;
        }

        .btn-submit {
            background: #2ecc71;
            color: #fff;
            width: 100%;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background: #27ae60;
        }

        .btn-edit {
            background: #3498db;
            color: #fff;
        }

        .btn-edit:hover {
            background: #2980b9;
        }

        .btn-delete {
            background: #e74c3c;
            color: #fff;
        }

        .btn-delete:hover {
            background: #c0392b;
        }

        .btn-cancel {
            background: #95a5a6;
            color: #fff;
        }

        .btn-cancel:hover {
            background: #7f8c8d;
        }

        .action-group {
            display: flex;
            gap: 5px;
        }

        /* メッセージアラート */
        .success-alert {
            background-color: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        /* エラーメッセージ用の赤いボックスのスタイル */
        .error-alert {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
            font-weight: bold;
        }
    </style>

    <div class="admin-main-layout">

        @include('admin.admin_sidebar')

        <div class="admin-container">
            <h1 class="page-title">カテゴリー管理</h1>

            @if (session('success'))
            <div class="success-alert">
                {{ session('success') }}
            </div>
            @endif

            @if (session('error'))
            <div class="error-alert">
                {{ session('error') }}
            </div>
            @endif

            <div class="category-management-wrap">

                <div class="category-create-box">
                    <h3>新規カテゴリー追加</h3>
                    <form action="/admin/categories" method="post">
                        @csrf
                        <div>
                            <label style="display:block; margin-bottom:5px;">カテゴリー名</label>
                            <input type="text" name="name" class="input-text" value="{{ old('name') }}" placeholder="例: スナック菓子" required>
                            @error('name')
                            <span style="color: red; font-size: 0.85rem; display: block; margin-top: 5px;">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-submit">追加する</button>
                    </form>
                </div>

                <div class="category-list-box">
                    <table class="category-table">
                        <thead>
                            <tr>
                                <th>カテゴリー名</th>
                                <th style="width: 180px;">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                            <tr>
                                <td>
                                    <span id="text-name-{{ $category->id }}">{{ $category->name }}</span>

                                    <form id="form-edit-{{ $category->id }}" action="/admin/categories/edit/{{ $category->id }}" method="post" style="display: none; margin: 0;">
                                        @csrf
                                        <input type="text" name="name" class="input-text" value="{{ $category->name }}" required>
                                    </form>
                                </td>
                                <td>
                                    <div id="btn-normal-{{ $category->id }}" class="action-group">
                                        <button type="button" class="btn btn-edit" onclick="showEditForm({{ $category->id }})">変更</button>

                                        <form action="/admin/categories/delete" method="post" style="margin: 0;"
                                            onsubmit="if ('{{ $category->products_exists ? 'true' : 'false' }}' === 'true') { return true; } else { return confirm('このカテゴリーを削除しますか？'); }">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $category->id }}">
                                            <button type="submit" class="btn btn-delete">削除</button>
                                        </form>
                                    </div>

                                    <div id="btn-edit-{{ $category->id }}" class="action-group" style="display: none;">
                                        <button type="button" class="btn btn-edit" onclick="submitEditForm({{ $category->id }})">保存</button>
                                        <button type="button" class="btn btn-cancel" onclick="hideEditForm({{ $category->id }})">戻る</button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>

    <script>
        function showEditForm(id) {
            document.getElementById('text-name-' + id).style.display = 'none';
            document.getElementById('btn-normal-' + id).style.display = 'none';

            document.getElementById('form-edit-' + id).style.display = 'block';
            document.getElementById('btn-edit-' + id).style.display = 'flex';
        }

        function hideEditForm(id) {
            document.getElementById('text-name-' + id).style.display = 'inline';
            document.getElementById('btn-normal-' + id).style.display = 'flex';

            document.getElementById('form-edit-' + id).style.display = 'none';
            document.getElementById('btn-edit-' + id).style.display = 'none';
        }

        function submitEditForm(id) {
            document.getElementById('form-edit-' + id).submit();
        }
    </script>

</body>

</html>