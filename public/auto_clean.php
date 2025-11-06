<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

/**
 * 🧹 自動清理舊文章腳本 (auto_clean.php)
 * ----------------------------------------
 * 用途：
 * - 定期刪除半年以前的文章：
 *   1️⃣ 已發布 (published) 且 publish_time 超過半年。
 *   2️⃣ 草稿 (draft) 且 updated_at 超過半年。
 * 
 * 使用方式：
 * - 本地端 CLI 測試：
 *     php /Applications/XAMPP/xamppfiles/htdocs/news_xinzhu/public/auto_clean.php
 * - 或瀏覽器測試：
 *     http://localhost/news_xinzhu/public/auto_clean.php?token=yourSecretKey
 * 
 * 實際上線後可設定 cron job：
 *     0 3 * * * php /path/to/public/auto_clean.php
 *     (每天凌晨3點自動清理)
 */

// === 安全檢查（防止隨意訪問）===
$token = $_GET['token'] ?? '';
$validToken = 'yourSecretKey'; // ← 可自行設定任意安全字串
if (php_sapi_name() !== 'cli' && $token !== $validToken) {
    http_response_code(403);
    exit('Forbidden');
}

// === 載入設定與 DB 類別 ===
require_once __DIR__ . '/../app/core/db.php';
require_once __DIR__ . '/../config/db.php';

// === 執行清理 ===
try {
    $db = new DB('articles');

    // 刪除「已發布」且 publish_time 超過半年
    $sql1 = "DELETE FROM articles
             WHERE status = 'published'
             AND publish_time < DATE_SUB(NOW(), INTERVAL 6 MONTH)";
    $affected1 = $db->exec($sql1);

    // 刪除「草稿」且 updated_at 超過半年
    $sql2 = "DELETE FROM articles
             WHERE status = 'draft'
             AND updated_at < DATE_SUB(NOW(), INTERVAL 6 MONTH)";
    $affected2 = $db->exec($sql2);

    // 計算總共影響的筆數
    $affected = $affected1 + $affected2;

    // === 寫入 Log ===
    $logMessage = "[" . date('Y-m-d H:i:s') . "] Auto clean executed. Rows deleted: {$affected}\n";
    file_put_contents(__DIR__ . '/../storage/auto_clean.log', $logMessage, FILE_APPEND);

    echo nl2br($logMessage); // 顯示於瀏覽器也比較整齊

} catch (Exception $e) {
    $errorMsg = "[" . date('Y-m-d H:i:s') . "] Error: " . $e->getMessage() . "\n";
    file_put_contents(__DIR__ . '/../storage/auto_clean.log', $errorMsg, FILE_APPEND);

    http_response_code(500);
    echo "Error: " . htmlspecialchars($e->getMessage());
}
