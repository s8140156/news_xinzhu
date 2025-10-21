<?php

class NewsController {
    public function index() {
        // 取得分類資料
        $categories = $this->getNewsCategories();

        // 指定頁面
        $content = APP_PATH . '/views/frontend/news/index.php';
        include APP_PATH . '/views/frontend/layouts/main.php';
    }

    private function getNewsCategories() {
        $db = new DB('news_categories');
        return $db->all("1 ORDER BY sort ASC");
    }






    
}








?>