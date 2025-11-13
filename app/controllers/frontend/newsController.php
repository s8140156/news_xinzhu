<?php

require_once APP_PATH . '/core/db.php';
require_once APP_PATH . '/core/helpers.php';

class NewsController {
    public function index() {
        // 取得新聞分類清單
        $categories = getNewsCategoryMap('sort ASC'); // nav使用
        $categoryList = getAllCategories('sort ASC'); // 卡片使用(完整資料)

        // 取得焦點新聞分類
        $dbCategory = new DB ('news_categories');
        $focusCategory = $dbCategory->all("is_focus = 1 LIMIT 1");
        $focusArticle = null;

        // 若有焦點分類 取該分類下最新一篇文章
        if(!empty($focusCategory)) {
            $focusCategory = $focusCategory[0];
            $dbArticle = new DB('articles');
            $latestFocus = $dbArticle->all( "category_id = ? AND status = 'published' ORDER BY publish_time DESC LIMIT 1",
                [$focusCategory['id']]);
            $focusArticle = $latestFocus ? $latestFocus[0] :  null;
        }
        // 每個分類取最新一篇新聞
        $dbArticle = new DB('articles');
        foreach($categoryList as &$cat) {
            $latest = $dbArticle->all(
                "category_id = ? AND status = 'published' ORDER BY publish_time DESC LIMIT 1",
                [$cat['id']]
            );
            if($latest) {
                $article = $latest[0];
                $cat['latest_article'] = $article;
                $cat['cover_image'] = getCoverImage($article);
            }else {
                $cat['latest_article'] = null;
                $cat['cover_image'] = BASE_URL . '/assets/frontend/images/default_cover.jpg';
            }
        }
        unset($cat);

        // 指定頁面
        $title = '首頁-馨築新聞網';
        $content = APP_PATH . '/views/frontend/news/index.php';
        include APP_PATH . '/views/frontend/layouts/main.php';
    }








    
}








?>