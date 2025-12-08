<?php

// 手動清理未使用封面圖片檔案(測試用)

define('ROOT_PATH', realpath(__DIR__ . '/..'));
define('APP_PATH', ROOT_PATH . '/app');

// 載入 DB
require_once __DIR__ . '/../app/core/db.php';
if (file_exists(APP_PATH . '/config.local.php')) {
    require_once APP_PATH . '/config.local.php';
} else {
    require_once APP_PATH . '/config.production.php';
}


// 封面資料夾
$coverDir = ROOT_PATH . '/public/uploads/articles/cover/';

// 檢查資料夾是否存在
if (!is_dir($coverDir)) {
    echo "Cover directory not found: {$coverDir}\n";
    exit;
}

// 取得所有封面檔
$files = glob($coverDir . '*');

// 取得資料表目前使用的封面檔
$db = new DB('articles');
$articles = $db->all();

$used = [];
foreach ($articles as $a) {
    if (!empty($a['cover_image'])) {
        $used[] = basename($a['cover_image']); // 只取檔名
    }
}

// 避免刪掉「使用者正在編輯還沒送出的封面」
// 例如 30 分鐘內新檔不刪
$protectSeconds = 1800; // 30 分鐘

$now = time();
$deleted = 0;

foreach ($files as $filePath) {
    $fileName = basename($filePath);

    // 若是使用中文檔名或隱藏檔，略過
    if (!is_file($filePath)) continue;

    // 🔥 若 DB 沒有使用這張圖 → 判斷是否該刪
    if (!in_array($fileName, $used)) {

        // 最近 30 分鐘內的檔案不刪（避免誤刪正在編輯的封面）
        if ($now - filemtime($filePath) < $protectSeconds) {
            echo "Skip (protected) → {$fileName}\n";
            continue;
        }

        // 刪除檔案
        unlink($filePath);
        echo "Deleted → {$fileName}\n";
        $deleted++;
    }
}

echo "\n清理完成，共刪除 {$deleted} 個封面檔案。\n";
