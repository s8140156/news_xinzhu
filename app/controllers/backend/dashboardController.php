<?php

class DashboardController {
    public function index() {
        $title = "後台layout demo";

        // 開啟buffer 載入主要內容頁
        ob_start();
        include APP_PATH . '/views/backend/dashboard/index.php';
        $content = ob_get_clean();

        // 套入母版layout
        include APP_PATH . '/views/backend/layouts/main.php';
    }
}


?>
