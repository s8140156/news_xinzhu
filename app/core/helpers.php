<?php 

/**
 * 全域共用函式區
 * 放置多個 Controller / View 都會用到的邏輯
 * 例如：分類、封面圖、格式化日期、字串截斷等
 */

require_once __DIR__ . '/db.php';

// 取得新聞分類對照表 id=>name
function getNewsCategoryMap($orderBy = 'id ASC') {
    $catDb = new DB('news_categories');
    $categories = [];
    foreach($catDb->all("1 ORDER BY $orderBy") as $cat) {
        $categories[$cat['id']] = $cat['name'];
    }
    return $categories;
}

// 取得完整分類清單
function getAllCategories($orderBy = 'sort ASC') {
    $catDb = new DB('news_categories');
    return $catDb->all("1 ORDER BY $orderBy");
}

// 取得文章封面圖片邏輯(可前後台共用)
function getCoverImage($article) {

    if (!empty($article['cover_image'])) {
        $cover = $article['cover_image'];

        // 將 URL 換成相對路徑
        $relative = str_replace(BASE_URL, '', $cover);
        $relative = ltrim($relative, '/');

        // 加入 "news_xinzhu/public"（你的專案結構）
        $realPath = $_SERVER['DOCUMENT_ROOT'] . '/news_xinzhu/public/' . $relative;

        // Debug：印出確認
        // echo "Check real path: $realPath<br>";

        if (file_exists($realPath)) {
            return $cover;
        }
    }

    return BASE_URL . '/assets/frontend/images/default_cover.jpg';
}













?>