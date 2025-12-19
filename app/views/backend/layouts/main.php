<!DOCTYPE html>
<html lang="zh-Hant">

<head>
    <meta charset="UTF-8">
    <title><?= $title ?? '後台管理系統' ?></title>

    <!-- Bootstrap CSS（CDN） -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= STATIC_URL ?>/assets/backend/vendor/fontawesome-free/css/all.min.css">

    <!-- SB Admin 2 -->
    <link rel="stylesheet" href="<?= STATIC_URL ?>/assets/backend/css/sb-admin-2.min.css">

    <!-- jQuery(從script拉上來先引入 不然拖曳功能會吃不到) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

     <!-- Popper（Bootstrap 4 Tooltip / Dropdown 需要） -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>


    <!-- 自訂 CSS -->
    <link rel="stylesheet" href="<?= STATIC_URL ?>/assets/backend/css/style.css">

    <style>
    /* 強制隱藏 CKEditor 不安全提示 — 保證即使在 iframe 外層也生效 */
    body .cke_notifications_area,
    body .cke_notification,
    body .cke_notification_message {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
        height: 0 !important;
        overflow: hidden !important;
        pointer-events: none !important;
        z-index: -1 !important;
    }
    </style>

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
<script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip({
    html: true,
    placement: 'right',
    container: 'body'
  });
});
</script>