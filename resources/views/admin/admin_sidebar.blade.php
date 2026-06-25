<button type="button" class="sidebar-open-btn" onclick="toggleAdminSidebar()" title="メニューを開く">
    ☰
</button>

<div id="adminSidebarOverlay" class="admin-sidebar-overlay" onclick="toggleAdminSidebar()"></div>

<aside id="adminSidebar" class="admin-sidebar-drawer">
    <div class="sidebar-widget">
        <button type="button" class="sidebar-close-btn" onclick="toggleAdminSidebar()">×</button>
        
        <h2 class="widget-title">商品管理</h2>
        <ul class="admin-menu-list">
            <li><a href="/admin/products" class="menu-link border-blue">商品一覧画面へ</a></li>
            <li><a href="/admin/products/create" class="menu-link border-green">＋ 新規商品追加画面へ</a></li>
        </ul>

        <h2 class="widget-title">カテゴリー管理</h2>
        <ul class="admin-menu-list">
            <li><a href="/admin/categories" class="menu-link border-purple">カテゴリー一覧</a></li>
        </ul>

        <h2 class="widget-title">ユーザー管理</h2>
        <ul class="admin-menu-list">
            <li><a href="/admin/users" class="menu-link border-bread">ユーザー一覧</a></li>
        </ul>

        <h2 class="widget-title">お問い合わせ管理</h2>
        <ul class="admin-menu-list">
            <li><a href="/admin/contact" class="menu-link border-yellow">お問い合わせ一覧</a></li>
            <li><a href='/admin/trash' class="menu-link border-black">ゴミ箱</a></li>
        </ul>
    </div>
</aside>

<style>
/* 🔴 Gemini風のサイドバー開くボタン */
.sidebar-open-btn {
    position: fixed;
    top: 12px;
    left: 12px;
    z-index: 9999; /* 最前面を保証 */
    
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;       /* 完全な丸型 */
    
    background-color: transparent;
    color: #444746;           /* シックなダークグレー */
    border: none;
    cursor: pointer;
    font-size: 20px;
    transition: background-color 0.2s ease;
}

/* ホバー時にふわっと薄いグレーの丸型背景を表示 */
.sidebar-open-btn:hover {
    background-color: rgba(68, 71, 70, 0.08);
    color: #1f1f1f;
}

/* 背景の半透明カバー */
.admin-sidebar-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.4);
    z-index: 10000;
}

/* サイドバー本体の引き出し設定 */
.admin-sidebar-drawer {
    position: fixed;
    top: 0;
    left: -300px; /* 初期状態は画面外 */
    width: 280px;
    height: 100%;
    background-color: #fff;
    z-index: 10001;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.3);
    transition: left 0.3s ease;
    padding: 20px;
    box-sizing: border-box;
    overflow-y: auto;
}

/* JSでこのクラスがつくとスライドイン */
.admin-sidebar-drawer.open {
    left: 0 !important;
}

/* 閉じるボタン */
.sidebar-close-btn {
    position: absolute;
    top: 15px;
    right: 15px;
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #999;
    transition: color 0.2s;
}
.sidebar-close-btn:hover {
    color: #333;
}

/* メニュー内の各タイトル */
.widget-title {
    font-size: 1.05rem;
    border-bottom: 2px solid #333;
    padding-bottom: 5px;
    margin-top: 20px;
    color: #333;
}

.admin-menu-list {
    list-style: none;
    padding: 0;
    margin: 10px 0 20px 0;
}

/* 🟥 ハッキリわかる立体ボタンデザイン */
.menu-link {
    display: block;
    padding: 12px;
    background: #f8f9fa;
    border-radius: 6px;
    text-decoration: none;
    color: #333;
    font-weight: bold;
    margin-bottom: 10px;
    text-align: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    transition: all 0.2s ease;
    box-sizing: border-box;
}

/* ホバー時に全面が綺麗に色づく設定 */
.menu-link.border-blue:hover {
    background-color: #3498db;
    color: #fff !important;
}
.menu-link.border-green:hover {
    background-color: #2ecc71;
    color: #fff !important;
}.menu-link.border-purple:hover {
    background-color: #9b59b6;
    color: #fff !important;
}
.menu-link.border-bread:hover {
    background-color: #b45f06;
    color: #fff !important;
}
.menu-link.border-yellow:hover {
    background-color: #f1c40f;
    color: #fff !important;
}
.menu-link.border-black:hover {
    background-color: #2c3e50;
    color: #fff !important;
}


.border-blue { border-left: 6px solid #3498db; }
.border-green { border-left: 6px solid #2ecc71; }
.border-purple { border-left: 6px solid #9b59b6; }
.border-bread { border-left: 6px solid #b45f06; }
.border-yellow { border-left: 6px solid #f1c40f; }
.border-black { border-left: 6px solid #2c3e50; }

/* 未実装（グレーアウトボタン） */
.menu-link.disabled {
    color: #7f8c8d !important;
    background-color: #e0e0e0;
    cursor: not-allowed;
    border-left: 6px solid #7f8c8d;
    box-shadow: none;
    opacity: 0.6;
}
</style>

<script>
    function toggleAdminSidebar() {
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('adminSidebarOverlay');
        
        if (!sidebar || !overlay) return;

        sidebar.classList.toggle('open');
        
        if (sidebar.classList.contains('open')) {
            overlay.style.display = 'block';
        } else {
            overlay.style.display = 'none';
        }
    }
</script>