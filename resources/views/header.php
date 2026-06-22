<?php
// セッションが開始されていない場合は開始する
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ユーザー情報とカート数のダミーデータ（実際のシステムに合わせて書き換えてください）
$is_logged_in = isset($_SESSION['user_id']); // ログイン判定
$user_name = $is_logged_in ? htmlspecialchars($_SESSION['name'], ENT_QUOTES, 'UTF-8') : '';
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; // カート内の商品数
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ECサイト</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* カートのバッジ（数量表示）の位置微調整 */
        .badge-cart {
            position: absolute;
            top: -5px;
            right: -10px;
            font-size: 0.65rem;
        }
    </style>
</head>
<body>

<header class="navbar navbar-expand-lg navbar-light bg-light border-bottom sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold fs-3 text-primary" href="index.php">
            <i class="fa-solid fa-shop me-2"></i>MyShop
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#headerNavbar" aria-controls="headerNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="headerNavbar">
            <form class="d-flex mx-auto my-2 my-lg-0 w-50" action="search.php" method="GET">
                <div class="input-group">
                    <input type="search" name="keyword" class="form-control" placeholder="商品名を入力..." aria-label="Search">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i> </button>
                </div>
            </form>

            <div class="d-flex align-items-center gap-3">
                
                <a href="cart.php" class="btn btn-outline-dark position-relative me-2">
                    <i class="fa-solid fa-cart-shopping"></i> <?php if ($cart_count > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?= $cart_count ?>
                        </span>
                    <?php endif; ?>
                </a>

                <?php if ($is_logged_in): ?>
                    <span class="navbar-text me-2">
                        ようこそ、<strong><?= $user_name ?></strong> 様
                    </span>
                    <a href="logout.php" class="btn btn-sm btn-outline-danger">ログアウト</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-sm btn-primary">ログイン</a>
                <?php endif; ?>

            </div>
        </div>
    </div>
</header>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>