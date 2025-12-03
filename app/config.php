<?php
/**
 * 主設定檔：決定環境並載入對應設定
 */


// 2. 判斷環境：若 APP_ENV 有設定則使用，否則預設 local
$env = getenv('APP_ENV') ?: 'local';
define('APP_ENV', $env);

// 3. 組合設定檔路徑
$configFile = APP_PATH . "/config.{$env}.php";

// 4. 若設定檔不存在 → 報錯
if (!file_exists($configFile)) {
    die("找不到設定檔：{$configFile}");
}

// 5. 載入設定檔（此檔案會 define BASE_URL、DB_HOST 等）
require_once $configFile;