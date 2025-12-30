<?php

require_once APP_PATH . '/core/db.php';
require_once APP_PATH . '/core/helpers.php';

class SponsorPickController {

    public function index() {

        $catDB = new DB('news_categories');
        $categories = $catDB->query("
            SELECT id, name
            FROM news_categories
            ORDER BY sort ASC
        ");

        $now = date('Y-m-d H:i:s');
        $db = new DB('sponsor_picks');
        $sponsorPicks = $db->query("
            SELECT sp.*, 
                   a.title AS article_title,
                   c.name AS category_name
            FROM sponsor_picks sp
            LEFT JOIN articles a ON sp.article_id = a.id
            LEFT JOIN news_categories c ON sp.article_id = c.id
            ORDER BY sp.sort ASC, sp.id DESC");

        $content = APP_PATH . '/views/backend/sponsor_picks/index.php';
        include APP_PATH . '/views/backend/layouts/main.php';
    }
    public function store() {

        $db = new DB('sponsor_picks');
        $action = $_POST['action'] ?? 'update';
        $isSort = ($action === 'sort');

        // 先處理刪除
        if(!empty($_POST['delete_ids'])) {
            foreach($_POST['delete_ids'] as $deleteId) {
                $db->delete($deleteId);
            }
        }
        
        // 新增/更新
        $ids = $_POST['id'] ?? [];
        
        foreach($ids as $i => $id) {
            $now = date('Y-m-d H:i:s');
            // 啟用時間
            $startAt = !empty($_POST['start_at'][$i])
                ? $_POST['start_at'][$i]
                : $now;
            // 停用
            $endAt = !empty($_POST['end_at'][$i])
                ? $_POST['end_at'][$i]
                : null;

            $articleId = $_POST['article_id'][$i] ?: null;
            $article = null;
            $linkCount = 0;
            $articleTitle = '';
            if($articleId) {
                $article = (new DB('articles'))->find($articleId);
                if($article) {
                    $articleTitle = $article['title'];
                    $links = json_decode($article['links'], true);

                    if(is_array($links)) {
                        $linkCount = count($links);
                    }
                }
            }
            $data = [
                'sort' => (int)($_POST['sort'][$i] ?? ($i+1)),
            ];
            if(!$isSort) {
                $data += [
                    'start_at' => $startAt,
                    'end_at' => $endAt,
                    'article_category_id' => $_POST['article_category_id'][$i] ?: null,
                    'article_id' => $articleId,
                    'title' => $articleTitle,
                    'article_link_count' => $linkCount,
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
            }

            if($id === '(新)' || empty($id)) {
                $data['created_at'] = date('Y-m-d H:i:s');
                $db->insert($data);
            } else{
                $db->update($id, $data);
            }
        }
        if($isSort) {
            echo "<script>alert('排序成功！');window.location = '?page=sponsorpicks_index';</script>";
            return;
        }
        echo "<script>alert('更新成功！');window.location = '?page=sponsorpicks_index';</script>";
    }

    public function articleByCategory() {

        header('Contnet-Type: application/json; charset=utf8');

        $categoryId = $_GET['category_id'] ?? null;
        if(!$categoryId || !ctype_digit($categoryId)) {
            echo json_encode([]);
            exit;
        }

        $articles = getArticlesByCategory($categoryId);

        $result = [];
        foreach($articles as $a) {
            $result[] = [
                'id' => $a['id'],
                'title' => $a['title']
            ];
        }

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        exit;
    }




}
