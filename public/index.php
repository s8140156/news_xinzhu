<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

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

// 前台
// $page = $_GET['page'] ?? 'frontend_news';

// 後台
$page = $_GET['page'] ?? 'frontend_news';

// 前台
// switch($page) {
//     case 'frontend_news':
//         $controller = new NewsController();
//         $controller->index();
//         break;
//     default:
//         echo "404 Not Found";;
//         break;
// }

 // 後台
switch($page) {
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
        $controller->index();
        }
        break;
    case 'article_delete':
        $controller = new ArticleController();
        $controller->delete($_GET['id'] ?? null);
        break;
    case 'article_image_upload':
        $controller = new ArticleController();
        $controller->imageUpload();
        break;
}

// 呼叫controller
// $controller = new DashboardController();
// $controller = new ArticleController();
// $_POST = [
//             'title' => '這是測試文章',
//             'content' => '<p>這是測試的文章內容</p>',
//             'category_id' => 1,
//             'author' => 'julie',
//             'status' => 'draft',
//             'publish_time' => date('Y-m-d H:i:s'),
//             'views' => 0,
// ];
// $controller->store();
// $controller = new NewsCategoryController();
// if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['act']) && $_POST['act'] === 'addCategory') {
//     $controller->store();
// } else {
//     $controller->index();
// }








?>