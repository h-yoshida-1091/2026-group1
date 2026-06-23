<aside class="account-sidebar">
    <nav class="sidebar-nav">
        <a href="{{ route('orders.index') }}" 
           class="nav-item {{ request()->routeIs('orders.*') ? 'active' : '' }}">
           注文履歴
        </a>

        <a href="{{ route('account.edit') }}" 
           class="nav-item {{ request()->routeIs('account.edit') ? 'active' : '' }}">
           アカウント情報編集
        </a>

        <a href="{{ route('addresses.index') }}" 
           class="nav-item {{ request()->routeIs('addresses.*') ? 'active' : '' }}">
           お届け先一覧
        </a>

        <form method="POST" action="{{ route('logout') }}" style="display: none;" id="logout-form">
            @csrf
        </form>
        <a href="/logout" 
           class="nav-item" 
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
           ログアウト
        </a>
    </nav>
</aside>