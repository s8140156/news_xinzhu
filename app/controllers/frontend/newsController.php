<?php

require_once APP_PATH . '/core/db.php';
// require_once APP_PATH . '/core/helpers.php';
require_once APP_PATH . '/controllers/frontend/FrontendController.php';

class NewsController extends FrontendController {

    public function index() {
        // 取得分類完整資料(首頁卡片)
        $categoryList = getAllCategories('sort ASC');
        // 每個分類最新文章及封面
        foreach($categoryList as &$cat) {
            $latest = getLatestArticleByCategory($cat['id']);

            if($latest) {
                $cat['latest_article'] = $latest;
                $cat['cover_image'] = getCoverImage($latest);
            }else {
                $cat['latest_article'] = null;
                $cat['cover_image'] = BASE_URL . '/assets/frontend/images/default_cover.jpg';
            }
        }
        unset($cat);

        // 渲染首頁
        $this->render('frontend/news/index.php', [
            'categoryList' => $categoryList
        ]);
    }

    public function list() {
        $categoryId = $_GET['category'] ?? null;
        // 取得當前新聞分類
        $currentCategory = getNewsCategoryMap()[$categoryId] ?? null;
        // 文章列表
        $articles = getArticlesByCategory($categoryId);
        // var_dump($articles);
        // exit;

        $this->render('frontend/news/list.php', [
            'articles' => $articles,
            'currentCategory' => $currentCategory
        ]);
    }

    public function show() {
        $id = $_GET['id'] ?? null;

        $db = new DB('articles');
        $article = $db->find($id);

        $this->render('frontend/news/show.php', [
            'article' => $article
        ]);
    }








    
}








?>