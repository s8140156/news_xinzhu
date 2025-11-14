<?php 

require_once APP_PATH . '/core/helpers.php';

Class FrontendController {

    protected $categories; // 導覽列分類
    protected $focusArticle; // 右側焦點新聞
    protected $footerTags; // 頁尾標籤(現為測試資料)

    public function __construct() {
        // 導爛列-所有前台頁面可以自動取得
        $this->categories = getNewsCategoryMap('sort ASC');
        // 焦點新聞-所有前台右側可以自動取得
        $this->focusArticle = getFocusArticle();
        // 頁尾標籤-(目前是測試資料)
        $this->footerTags = [
            ['title' => '刊登廣告', 'url' => '#'],
            ['title' => '聯絡我們', 'url' => '#'],
            ['title' => '自定義 3', 'url' => '#'],
            ['title' => '自定義 4我是從frontcontroller來的', 'url' => '#'],
            ['title' => '自定義 789', 'url' => '#'],
        ];
    }

    /**
     * render()
     * 統一 render 前台頁面，並自動把共享資料帶入 layout
     */

    protected function render($viewPath, $data = []) {
        // 將自訂資料展開成變數給view用
        extract($data);

        // 自動注入共用資料給main.php
        $categories = $this->categories;
        $focusArticle = $this->focusArticle;
        $footerTags = $this->footerTags;

        // 主內容(view路徑)
        $content = APP_PATH . '/views/' . $viewPath;

        include APP_PATH . '/views/frontend/layouts/main.php';
    }








}




?>