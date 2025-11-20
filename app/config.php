<?php

/**
 * 全域設定檔
 * 包含：環境、BASE_URL、PUBLIC_PATH
 *
 * 未來換正式站、換 domain、換資料夾名稱，只要改這裡即可。
 */

// 🟦 環境
define('APP_ENV', 'local');  
// 可切換為： 'local' / 'staging' / 'production'


// 🟦 URL & 路徑設定
if (APP_ENV === 'local') {

    // 本機環境
    // define('BASE_URL', "http://localhost/news_xinzhu/public");
    define('BASE_URL', "http://192.168.0.136/news_xinzhu/public"); // 使用ip讓手機也可以瀏覽

} else {

    // 正式站環境（到時候你只要改這裡）
    define('BASE_URL', 'https://www.xxx.com.tw/news');

}
