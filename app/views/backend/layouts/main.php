<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? '後台管理系統' ?></title>

    <!-- ✅ Bootstrap CSS（CDN，穩定） -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- ✅ Font Awesome -->
    <link rel="stylesheet" href="/assets/backend/vendor/fontawesome-free/css/all.min.css">

    <!-- ✅ SB Admin 2 -->
    <link rel="stylesheet" href="/assets/backend/css/sb-admin-2.min.css">

    <!-- ✅ 你自己的自訂 CSS -->
    <link rel="stylesheet" href="/assets/backend/css/style.css">
</head>

<body id="page-top">

    <!-- 🔝 頂部導覽列 -->
    <?php include APP_PATH . '/views/backend/partials/topbar.php'; ?>

    <div id="wrapper">
        <!-- 📋 側邊選單 -->
        <?php include APP_PATH . '/views/backend/partials/sidebar.php'; ?>

        <!-- 🧱 主內容 -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content" class="p-4">
                <?= $content ?? '' ?>
            </div>

            <!-- ⚙️ 頁尾 -->
            <?php include APP_PATH . '/views/backend/partials/footer.php'; ?>
        </div>
    </div>

    <!-- 📦 共用 JS（放在 body 結尾最安全） -->
    <?php include APP_PATH . '/views/backend/partials/script.php'; ?>

</body>
</html>
