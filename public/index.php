<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// 基本環境設定
// 偵測當前專案根網址
$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http');
$baseUrl .= '://' . $_SERVER['HTTP_HOST'];
$baseUrl .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
// print_r($_SERVER);

define('BASE_URL', rtrim($baseUrl, '/'));
define('APP_PATH', realpath(__DIR__ . '/../app'));

// 載入controller
// 前台
require_once APP_PATH . '/controllers/frontend/newsController.php';

// 後台
// require_once APP_PATH . '/controllers/backend/dashboardController.php';
require_once APP_PATH . '/controllers/backend/newsCategoryController.php';
require_once APP_PATH . '/controllers/backend/articleController.php';

// 讀取頁面參數
$page = $_GET['page'] ?? 'frontend_news';


// 路由控制區
switch($page) {

    // 前台頁面
    case 'frontend_news':
        $controller = new NewsController();
        $controller->index();
        break;

    case 'frontend_news_show': // 顯示單篇文章
        $controller = new NewsController();
        $controller->show($_GET['id'] ?? null);
        break;


    // 後台：文章管理
    case 'article_index':
        $controller = new ArticleController();
        $controller->index();
        break;
    case 'article_create':
        $controller = new ArticleController();
        $controller->create();
        break;
    case 'article_store':
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $controller = new ArticleController();
            $controller->store();
        } else {
            echo "請使用表單送出資料";
        }
        break;
    case 'article_edit':
        $controller = new ArticleController();
        $controller->edit($_GET['id'] ?? null);
        break;
    case 'article_update':
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $controller = new ArticleController();
        $controller->update($_POST);
        }
        break;
    case 'article_delete':
        $controller = new ArticleController();
        $controller->delete($_GET['id'] ?? null);
        break;

    // 處理CKEditor圖片上傳
    case 'article_image_upload':
        $controller = new ArticleController();
        $controller->imageUpload();
        break;

    
    // 後台：新聞分類管理
    case 'category_index':
        $controller = new NewsCategoryController();
        $controller->index();
        break;
    case 'category_store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new NewsCategoryController();
            $controller->store();
        } else {
            echo "請使用表單送出資料";
        }
        break;


    // 預設或錯誤頁
    default:
        echo "<h2>404 - Page Not Found</h2>";
        break;
}




?>