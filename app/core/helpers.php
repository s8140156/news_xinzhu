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
    // 有封面欄位
    if (!empty($article['cover_image'])) {
        $cover = $article['cover_image'];

        // 若為完整網址
        if (preg_match('/^https?:\/\//', $cover)) {
            // 嘗試確認檔案是否存在於本地（避免被刪除卻仍顯示）
            $path = APP_PATH . '/../public/' . ltrim(parse_url($cover, PHP_URL_PATH), '/');
            if (file_exists($path)) {
                return $cover;
            }
        } else {
            // 相對路徑情況
            $path = APP_PATH . '/../public/' . ltrim($cover, '/');
            if (file_exists($path)) {
                return BASE_URL . '/' . ltrim($cover, '/');
            }
        }
    }

    // 沒封面或檔案不存在 → fallback 預設封面
    return BASE_URL . '/assets/frontend/images/default_cover.jpg';
}












?>