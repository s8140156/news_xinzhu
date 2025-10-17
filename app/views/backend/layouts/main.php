<!DOCTYPE html>
<html lang="zh-Hant">

<head>
    <meta charset="UTF-8">
    <title><?= $title ?? '後台管理系統' ?></title>

    <!-- Bootstrap CSS（CDN） -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/backend/vendor/fontawesome-free/css/all.min.css">

    <!-- SB Admin 2 -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/backend/css/sb-admin-2.min.css">

    <!-- 自訂 CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/backend/css/style.css">
</head>

<body id="page-top">
    <div id="wrapper">
        <!-- 側邊選單 -->
        <?php include APP_PATH . '/views/backend/partials/sidebar.php'; ?>

        <!-- 主內容 -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- 頂部導覽列 -->
                <?php include APP_PATH . '/views/backend/partials/topbar.php'; ?>

                <!-- 主內容 -->
                <div class="container-fluid p-4">
                    <?php if(isset($content) && file_exists($content)) {
                    include $content;
                } ?>
                </div>
            </div>

            <!-- 頁尾 -->
            <?php include APP_PATH . '/views/backend/partials/footer.php'; ?>
        </div>
    </div>

    <!-- 共用 JS -->
    <?php include APP_PATH . '/views/backend/partials/script.php'; ?>

</body>

</html>