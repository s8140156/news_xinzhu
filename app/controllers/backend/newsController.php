<?php

require_once APP_PATH . '/core/db.php';

class NewsController {

    public function index() {
        //建立DB連線
        $db =new DB('news_categories');
        $categories = $db->all("1 ORDER BY `is_focus` DESC, `sort` ASC");

        //指定頁面
        $content = APP_PATH . '/views/backend/news_categories/index.php';
        //將資料傳給view
        include APP_PATH . '/views/backend/layouts/main.php';
    }









}











?>