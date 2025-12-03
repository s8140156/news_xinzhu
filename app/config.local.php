<?php
/**
 * 本機環境設定（local）
 */

// 1. 專案 BASE_URL（你的本地網址）
define('BASE_URL', 'http://192.168.0.136/news_xinzhu/public');
define('STATIC_URL', BASE_URL);
// define('BASE_URL', 'http://192.168.0.136/news_xinzhu');

// 2. 資料庫設定（本機的 MySQL）
define('DB_HOST', 'localhost');
define('DB_NAME', 'newsXinzhu');
define('DB_USER', 'root');
define('DB_PASS', '');

// 3. 上傳路徑與 URL（統一路徑常數）
// 實體路徑（存放在 public/uploads/...）
define('UPLOAD_PATH', ROOT_PATH . '/public/uploads');

// 對外可訪問網址（上傳圖要用這個）
define('UPLOAD_URL', BASE_URL . '/uploads');
