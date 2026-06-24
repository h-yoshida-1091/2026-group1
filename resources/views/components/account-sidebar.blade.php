<head>
   <link href="{{ asset('css/account_edit.css') }}"
</head>

<aside class="account-sidebar">
    <nav class="sidebar-nav">
         <a href="/account/edit" 
           class="nav-item {{ request()->routeIs('account.edit') ? 'active' : '' }}">
           アカウント情報編集
        </a>

        <a href="/purchase/history" 
           class="nav-item {{ request()->routeIs('purchase.history') ? 'active' : '' }}">
           注文履歴
        </a>

        <a href="#" 
           class="nav-item {{ request()->routeIs('addresses.*') ? 'active' : '' }}">
           お届け先一覧
        </a>

         <form method="POST" action="{{ route('account.destroy') }}" style="display: none;" id="delete-form">
            @csrf
            @method('DELETE')
            <input type="hidden" name="delete_password" id="hidden-delete-password">
         </form>

         <a href="#" 
            class="btn btn-danger" 
            onclick="event.preventDefault(); 
            // ① 画面に文字入力付きのポップアップを出す
            let password = prompt('本人確認のため、現在のパスワードを入力してください：'); 
            // ② キャンセルされず、かつ何かしら入力されていた場合
            if (password !== null && password.trim() !== '') { 
                // ③ 隠し入力欄にパスワードをセットして送信
                document.getElementById('hidden-delete-password').value = password;
                document.getElementById('delete-form').submit(); 
               }">
            アカウントを削除する
         </a>
    </nav>
</aside>