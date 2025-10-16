<?php

require_once APP_PATH . '/core/db.php';

class NewsController {
    public function index() {
        $newsDB = new DB('test_articles');
        // $news = $newsDB->find(2);
        // $news = $newsDB->find(['title'=>'這是第一篇文章']);
        // $news = $newsDB->find(['id'=>1,'content'=>'我是內文A']);
        // $news = $newsDB->update(7, ['title'=>'這是第七篇文章','content'=>'我是內文G','created_at'=> date('Y-m-d H:i:s')]);
        $news = $newsDB->delete(6);


        echo '<h1>資料串接成功</h1>';
        echo '<pre>';
        echo $news ? "刪除成功" : "刪除失敗";
        echo '</pre>';
    }

}











?>