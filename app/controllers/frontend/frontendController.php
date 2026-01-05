<?php 

require_once APP_PATH . '/core/helpers.php';

Class FrontendController {

    protected $categories; // 導覽列分類
    protected $focusArticle; // 右側焦點新聞
    protected $footerTags; // 頁尾標籤
    protected $partners; // 合作媒體
    protected $isMobile; // 偵測是否是否手機


    public function __construct() {
        // 導覽列-所有前台頁面可以自動取得
        $this->categories = getNewsCategoryMap('sort ASC');
        // 焦點新聞-所有前台右側可以自動取得
        $this->focusArticle = getFocusArticle();
        $this->footerTags = getActiveFooterArticles();
        $this->partners = getActivePartners();
        $this->isMobile = preg_match(
            '/Android|iPhone|iPad|iPod|Opera Mini|IEMobile/i',
            $_SERVER['HTTP_USER_AGENT']
        );

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
        $partners = $this->partners;
        
        // 主內容(view路徑)
        $content = APP_PATH . '/views/' . $viewPath;
        include APP_PATH . '/views/frontend/layouts/main.php';
    }

    protected function renderMobile($viewPath, $data = []) {
        // 將自訂資料展開成變數給view用
        extract($data);

        // 自動注入共用資料給main.php
        $categories = $this->categories;
        $focusArticle = $this->focusArticle;
        $footerTags = $this->footerTags;
        $partners = $this->partners;
        $isMobile = $this->isMobile;

        // 主內容 (mobile view 路徑)
        $mobileContent = APP_PATH . '/views/' . $viewPath;
        include APP_PATH . '/views/frontend/layouts/mobile.php';
    }



    
}




?>