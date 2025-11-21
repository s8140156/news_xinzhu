<?php
/**
 * 主設定文件：自動載入對應環境設定
 * 
 * APP_ENV 應由 server 本身決定：
 * - local：本機環境（XAMPP）
 * - production：正式環境（EC2 / Lightsail / 真實 domain）
 */

if (!defined('APP_PATH')) {
    define('APP_PATH', realpath(__DIR__ . '/..'));
}

// 判斷環境：
// 若 server 有 APP_ENV 環境變數 → 優先使用
// 若沒有 → 預設為 local（本機）
$env = getenv('APP_ENV') ?: 'local';
define('APP_ENV', $env);

// 根據環境載入設定檔
$configFile = APP_PATH . "/config.{$env}.php";

if (!file_exists($configFile)) {
    die("找不到設定檔：{$configFile}");
}

require_once $configFile;
