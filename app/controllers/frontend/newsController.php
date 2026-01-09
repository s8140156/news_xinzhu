<?php

require_once APP_PATH . '/core/helpers.php';
require_once APP_PATH . '/controllers/frontend/frontendController.php';

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
                $cat['cover_image'] = BASE_URL . '/assets/frontend/images/oops_cover.png';
            }
        }
        unset($cat);

        if($this->isMobile) {
            // 渲染手機版首頁
            $this->renderMobile('frontend/mobile/home.php', [
                'categoryList' => $categoryList
            ]);
            return;

        }

        // 渲染桌機首頁
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

        if($this->isMobile) {
            // 渲染手機版首頁
            $this->renderMobile('frontend/mobile/list.php', [
                'articles' => $articles,
                'currentCategory' => $currentCategory,
                'categoryId' => $categoryId
            ]);
            return;
        }

        $this->render('frontend/news/list.php', [
            'articles' => $articles,
            'currentCategory' => $currentCategory
        ]);
    }

    public function show() {
        $id = $_GET['id'] ?? null;

        $db = new DB('articles');
        $article = $db->find($id);

        // 一進文章內頁 紀錄該篇文章點擊數
        $db->update($id, [
            'views' => ($article['views'] ?? 0) + 1
        ]);

        // 取出狀態
        $statusLabel = '';
        switch ($article['status']) {
            case 'draft':
                $statusLabel = '草稿';
                break;
            case 'scheduled':
                $statusLabel = '排程中';
                break;
            case 'published':
                $statusLabel = '已發布';
                break;
        }
        // link連擊數處理
        // 取出content
        $content = $article['content'];
        // 取links資料
        $links = json_decode($article['links'], true) ?? [];
        // DOM解析
        $dom = new DOMDocument();
        libxml_use_internal_errors(true); 
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $content);
        libxml_clear_errors();

        $anchors = $dom->getElementsByTagName('a');
        foreach($anchors as $i => $a) {
            if(isset($links[$i])) {
                $articleId = $article['id'];
                $a->setAttribute("onclick", "recordLinkClick($articleId, $i)");
                $a->setAttribute("target", "_blank");
            }
        }
        // 回存成新HTML
        $body = $dom->getElementsByTagName('body')->item(0);
        $article['content_html'] = $dom->saveHTML($body);


        $categoryMap = getNewsCategoryMap();
        $categoryName = $categoryMap[$article['category_id']] ?? '未分類';

        if($this->isMobile) {
            // 渲染手機版首頁
            $this->renderMobile('frontend/mobile/show.php', [
                'article' => $article,
                'categoryName' => $categoryName,
                'statusLabel' => $statusLabel
            ]);
            return;
        }

        $this->render('frontend/news/show.php', [
            'article' => $article,
            'categoryName' => $categoryName,
            'statusLabel' => $statusLabel
        ]);
    }

    public function recordLinkClick() {
        $id = $_POST['id'] ?? null;
        $index = $_POST['index'] ?? null;

        if ($id === null || $id === '' || $index === null || $index === '') {
            echo "error";
            return;
        }
        $db = new DB('articles');
        $article = $db->find($id);

        // 確認 link_clicks 是否為 json
        $raw = $article['link_clicks'] ?? '';
        if ($raw === null || $raw === '') {
            $clicks = [];
        } else {
            $clicks = json_decode($raw, true);
            if (!is_array($clicks)) {
                $clicks = [];
            }
        }

        // 計算點擊
        $clicks[$index] = ($clicks[$index] ?? 0) + 1;

        $db->update($id, [
            'link_clicks' => json_encode($clicks, JSON_UNESCAPED_UNICODE)
        ]);

        echo "ok";
    }

    public function search() {
        $keyword = trim($_GET['keyword'] ?? '');

        if($keyword === '') {
            $results = [];
            include_once APP_PATH . "/views/frontend/search.php";
            return;
        }

        $db = new DB('articles');
        $results = $db->query("
            SELECT id, title, author, cover_image, publish_time, content
            FROM articles
            WHERE status = 'published'
            AND (title LIKE ? OR content LIKE ? OR author LIKE ?)
            ORDER BY publish_time DESC
        ", ["%$keyword%", "%$keyword%", "%$keyword%"]);

        if($this->isMobile) {
            // 渲染手機版首頁
            $this->renderMobile('frontend/mobile/search.php', [
                'results' => $results,
                'keyword' => $keyword
            ]);
            return;
        }

        // 渲染search
        $this->render('frontend/news/search.php', [
            'results' => $results,
            'keyword' => $keyword
        ]);
    }

    // 廣告區(sponsorpicks) 顯示邏輯
    public function apiSponsorPicks() {
        header('Content-Type: application/json');

        $db = new DB('sponsor_picks');

        $rows = $db->query("
            SELECT id, article_id, title, article_link_count
            FROM sponsor_picks
            WHERE start_at <= NOW() 
            AND (end_at IS NULL OR end_at >= NOW())
            ORDER BY sort ASC, start_at DESC
            LIMIT 20 
        ");

        echo json_encode([
            'success' => true,
            'data' => $rows
        ]);
        exit;
    }

    public function sponsorClick() {
        $sponsorId = $_GET['id'] ?? 0;
        if(!$sponsorId) {
            header('Location: ' . BASE_URL);
            exit;
        }
        $sponsorDB = new DB('sponsor_picks');
        $sponsor = $sponsorDB->find($sponsorId);
        if(!$sponsor || empty($sponsor['article_id'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
        // 廣告區點擊統計
        $sponsorDB->update($sponsorId,[
            'click_count' => $sponsor['click_count'] + 1,
        ]);

        header('Location: ' . BASE_URL . '/?page=news_show&id=' . $sponsor['article_id']);
        exit;
    }

    public function partnerClick() {
        $partnerId = $_GET['id'] ?? 0;
        if(!$partnerId) {
            header('Location: ' . BASE_URL);
            exit;
        }
        $db = new DB('partners');
        $partner = $db->find($partnerId);
        if(!$partner || empty($partner['link_url'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
        // 廣告區點擊統計
        $db->update($partnerId,[
            'click_count' => $partner['click_count'] + 1,
        ]);

        header('Location: ' . $partner['link_url']);
        exit;
    }

    public function loadMore() {
        header('Content-Type: application/json');

        $lastId = $_GET['last_id'] ?? 0;
        $categoryId = $_GET['category_id'] ?? 0;
        $lastPublishTime = $_GET['last_publish_time'] ?? null;
        $limit = 10;

        if(!$categoryId || !$lastPublishTime || !$lastId) {
            echo json_encode([
                'success' => false,
                'data' => []]);
            exit;    
        }

        $db = new DB('articles');
        $articles = $db->query(
            "SELECT id, title, publish_time
            FROM articles
            WHERE status = 'published'
            AND category_id = ?
            AND (
                publish_time < ?
            OR (publish_time = ? AND id < ?)
            )
            ORDER BY publish_time DESC, id DESC
            LIMIT " . ($limit + 1), [$categoryId, $lastPublishTime, $lastPublishTime, $lastId]);

        $hasMore = count($articles) > $limit;
        if ($hasMore) {
            array_pop($articles);
        }

        echo json_encode([
            'success' => true,
            'data' => $articles,
            'has_more' => $hasMore
        ]);
        exit;
    }








    
}








?>