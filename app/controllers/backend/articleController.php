<?php

require_once APP_PATH . '/core/db.php';

class ArticleController {

    public function create() {
        //建立DB連線
        $db =new DB('news_categories');
        $categories = $db->all("1 ORDER BY sort ASC");

        $content = APP_PATH . '/views/backend/articles/create.php';
        include APP_PATH . '/views/backend/layouts/main.php';
    }

    public function store() {
        $db = new DB('articles');
        $data = [
            'title' => $_POST['title'],
            'content' => $_POST['content'],
            'category_id' => $_POST['category_id'],
            'author' => $_POST['author'],
            'status' => $_POST['status'],
            'publish_time' => $_POST['publish_time'],
            'views' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if(!empty($_POST['images_json'])) {
            $data['images'] = $_POST['images_json'];
        }

        $db->insert($data);
        header("Location: index.php?page=article_list");
        exit;
    }









}

?>