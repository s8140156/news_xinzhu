<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

/**
 * 自動發布排程腳本
 * ---------------------------------
 * 用途：
 * - 自動將應發布文章 (status='scheduled' 且 publish_time <= NOW()) 改為 published
 * - 可被瀏覽器、CLI、或 cron job 執行
 * ---------------------------------
 * 使用方式：
 * - 本地端測試：
 *     php /Applications/XAMPP/xamppfiles/htdocs/news_xinzhu/public/auto_publish.php
 * - 或瀏覽器開啟：
 *     http://localhost/news_xinzhu/public/auto_publish.php?token=yourSecretKey
 */

// === 定義 ROOT_PATH / APP_PATH（必須） ===
define('ROOT_PATH', realpath(__DIR__ . '/..'));
define('APP_PATH', ROOT_PATH . '/app');

// === 安全檢查（防止隨意訪問）===
$token = $_GET['token'] ?? '';
$validToken = 'yourSecretKey'; // ← 你可以改成任意一組字串
if (php_sapi_name() !== 'cli' && $token !== $validToken) {
    http_response_code(403);
    exit('Forbidden');
}

// === 載入設定與 DB 類別 ===
require_once APP_PATH . '/core/db.php';
if (file_exists(APP_PATH . '/config.local.php')) {
    require_once APP_PATH . '/config.local.php';
} else {
    require_once APP_PATH . '/config.production.php';
}

// === 自動更新狀態 ===
try {
    $db = new DB('articles');
    $affected = $db->exec("
        UPDATE articles
        SET status = 'published',
            updated_at = NOW()
        WHERE status = 'scheduled'
        AND publish_time <= NOW()
    ");

    // === 寫入 Log ===
    $logMessage = "[" . date('Y-m-d H:i:s') . "] Auto publish executed. Rows updated: {$affected}\n";
    file_put_contents(ROOT_PATH . '/storage/auto_publish.log', $logMessage, FILE_APPEND);

    echo $logMessage;
} catch (Exception $e) {
    file_put_contents(ROOT_PATH . '/storage/auto_publish.log', 
        "[" . date('Y-m-d H:i:s') . "] Error: " . $e->getMessage() . "\n", FILE_APPEND);
    http_response_code(500);
    echo "Error: " . $e->getMessage();
}
