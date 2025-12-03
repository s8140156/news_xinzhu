<?php
/**
 * 正式機設定（production）
 */

// 1. 正式機 BASE_URL
define('BASE_URL', 'https://hc-life.news');
define('STATIC_URL', 'https://hc-life.news');

// 2. 正式機 DB 設定
define('DB_HOST', 'localhost');
define('DB_NAME', 'newsXinzhu');
define('DB_USER', 'root');
define('DB_PASS', 'JTG@1qaz@WSX');

// 3. 上傳路徑（正式機也是存在 public/uploads 下）
define('UPLOAD_PATH', ROOT_PATH . '/public/uploads');

define('UPLOAD_URL', BASE_URL . '/uploads');
