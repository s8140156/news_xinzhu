<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

define('ROOT_PATH', realpath(__DIR__ . '/..')); //定義專案根目錄
define('APP_PATH', ROOT_PATH . '/app'); // 定義應用程式目錄
require_once APP_PATH . '/config.php'; // 載入config,自動判斷環境(local/production)

// 載入controller
// 前台
require_once APP_PATH . '/controllers/frontend/newsController.php';
require_once APP_PATH . '/controllers/frontend/footerController.php';

// 後台
require_once APP_PATH . '/controllers/backend/newsCategoryController.php';
require_once APP_PATH . '/controllers/backend/articleController.php';
require_once APP_PATH . '/controllers/backend/authController.php';
require_once APP_PATH . '/controllers/backend/sysuserController.php';
require_once APP_PATH . '/controllers/backend/sponsorPickController.php';
require_once APP_PATH . '/controllers/backend/profileController.php';
require_once APP_PATH . '/controllers/backend/partnerController.php';
require_once APP_PATH . '/controllers/backend/footerArticleController.php';

// 處理後台未登入導向登入頁
$backendPages = [
    'article',
    'category',
    'sponsorpicks',
    'partner',
    'footer',
    'sysuser',
    'profile',
];

// 讀取頁面參數
$page = $_GET['page'] ?? 'frontend_news';

$isBackend = in_array($page, $backendPages);
// // 判斷是否為後台page
$isBackend = false;
foreach($backendPages as $prefix) {
    if(str_starts_with($page, $prefix)) {
        $isBackend = true;
        break;
    }
}
// // 未登入進後台=>導向login
if($isBackend && empty($_SESSION['user_id'])) {
    header("Location: ?page=login");
    exit;
}


// 路由控制區
switch($page) {

    // 前台頁面
    case 'frontend_news':
        $controller = new NewsController();
        $controller->index();
        break;
    case 'news_list': // 顯示分類文章列表
        $controller = new NewsController();
        $controller->list();
        break;

    case 'news_show': // 顯示單篇文章
        $controller = new NewsController();
        $controller->show($_GET['id'] ?? null);
        break;
    case 'api_link_click': // 統計連結點擊API
        $controller = new NewsController();
        $controller->recordLinkClick();
        break;
    case 'search': // 新聞關鍵字搜尋
        $controller = new NewsController();
        $controller->search();
        break;
    case 'api_sponsorpicks_active': // 廣告區走馬燈顯示邏輯API
        $controller = new NewsController();
        $controller->apiSponsorPicks();
        break;
    case 'api_sponsorpicks_click': // 統計廣告區點擊API
        $controller = new NewsController();
        $controller->sponsorClick();
        break;
    case 'api_partner_click': // 統計合作媒體點擊API
        $controller = new NewsController();
        $controller->partnerClick();
        break;
    case 'api_news_load_more': // 載入更多文章分類API
        $controller = new NewsController();
        $controller->loadMore();
        break;
    case 'news_footer_show': // 顯示頁尾標籤單篇文章
        $controller = new FooterController();
        $controller->show($_GET['id'] ?? null);
        break;
    case 'api_footer_link_click': // 統計頁尾標籤連結點擊API
        $controller = new FooterController();
        $controller->recordLinkClick();
        break;




    // 後台：文章管理
    case 'article_index':
        requirePermission('view', MODULE_ARTICLE);
        $controller = new ArticleController();
        $controller->index();
        break;
    case 'article_create':
        requirePermission('create', MODULE_ARTICLE);
        $controller = new ArticleController();
        $controller->create();
        break;
    case 'article_store':
        requirePermission('create', MODULE_ARTICLE);
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $controller = new ArticleController();
            $controller->store();
        } else {
            echo "請使用表單送出資料";
        }
        break;
    case 'article_edit':
        requirePermission('edit', MODULE_ARTICLE);
        $controller = new ArticleController();
        $controller->edit($_GET['id'] ?? null);
        break;
    case 'article_update':
        requirePermission('edit', MODULE_ARTICLE);
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $controller = new ArticleController();
        $controller->update($_POST);
        }
        break;
    case 'article_delete':
        requirePermission('delete', MODULE_ARTICLE);
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
        requirePermission('view', MODULE_CATEGORY);
        $controller = new NewsCategoryController();
        $controller->index();
        break;
    case 'category_store':
        requirePermission('create', MODULE_CATEGORY);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new NewsCategoryController();
            $controller->store();
        } else {
            echo "請使用表單送出資料";
        }
        break;

    // 後台：廣告管理
    case 'sponsorpicks_index':
        requirePermission('view', MODULE_SPONSORED);
        $controller = new SponsorPickController();
        $controller->index();
        break;
    case 'sponsorpicks_store':
        requirePermission('create', MODULE_SPONSORED);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new SponsorPickController();
            $controller->store();
        } else {
            echo "請使用表單送出資料";
        }
        break;
    case 'api_sponsorpicks_article_by_category':
        $controller = new SponsorPickController();
        $controller->articleByCategory();
        break;

    // 後台：合作媒體管理
    case 'partner_index':
        requirePermission('view', MODULE_PARTNER);
        $controller = new PartnerController();
        $controller->index();
        break;
    case 'partner_store':
        requirePermission('create', MODULE_PARTNER);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller = new PartnerController();
        $controller->store();
        }
        break;

    // 後台：頁尾標籤管理
    case 'footer_index':
        requirePermission('view', MODULE_FOOTER);
        $controller = new footerArticleController();
        $controller->index();
        break;
    case 'footer_create':
        requirePermission('create', MODULE_FOOTER);
        $controller = new footerArticleController();
        $controller->create();
        break;
    case 'footer_store':
        requirePermission('create', MODULE_FOOTER);
        $controller = new footerArticleController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->store();
        }
        break;
    case 'footer_edit':
        requirePermission('edit', MODULE_FOOTER);
        $controller = new footerArticleController();
        $controller->edit($_GET['id'] ?? null);
        break;
    case 'footer_update':
        requirePermission('edit', MODULE_FOOTER);
        $controller = new footerArticleController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->update($_POST);
        }
        break;
    case 'footer_delete':
        requirePermission('delete', MODULE_FOOTER);
        $controller = new footerArticleController();
        $controller->delete($_GET['id'] ?? null);
        break;
    // 處理CKEditor圖片上傳
    case 'footer_image_upload':
        $controller = new footerArticleController();
        $controller->footerImageUpload();
        break;
    // 處理footer排序
    case 'api_footer_sort':
        requirePermission('create', MODULE_FOOTER);
        $controller = new footerArticleController();
        $controller->updateSort();
        break;

    // 後台：登入認證/登出
    // 登入login
    case 'login':
        $controller = new AuthController();
        $controller->login();
        break;
    // 登入驗證
    case 'doLogin':
        $controller = new AuthController();
        $controller->doLogin();
        break;
    // 修改密碼
    case 'change_password':
        $controller = new AuthController();
        $controller->changePassword();
        break;
    // 忘記密碼
    case 'forget_password':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $controller = new AuthController();
        $controller->forgetPassword();
        }
        break;
    case 'doChangePassword':
        $controller = new AuthController();
        $controller->doChangePassword();
        break;
    // 登出logout
    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;

    // 最高管理者帳號管理(SA)
    case 'sysuser_list':
        requirePermission('view', MODULE_SYSUSER);
        $controller = new SysuserController();
        $controller->index();
        break;
    case 'sysuser_create':
        requirePermission('create', MODULE_SYSUSER);
        $controller = new SysuserController();
        $controller->create();
        break;
    case 'sysuser_store':
        requirePermission('create', MODULE_SYSUSER);
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $controller = new SysuserController();
        $controller->store();
        }
        break;
    case 'sysuser_edit':
        requirePermission('edit', MODULE_SYSUSER);
        $controller = new SysuserController();
        $controller->edit($_GET['id'] ?? null);
        break;
    case 'sysuser_update':
        requirePermission('edit', MODULE_SYSUSER);
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $controller = new SysuserController();
        $controller->update($_POST);
        }
        break;
    case 'sysuser_toggle_status':
        $controller = new SysuserController();
        // $controller->toggle($_GET['id'] ?? null);
        break;
    case 'sysuser_delete':
        requirePermission('delete', MODULE_SYSUSER);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            abort403('非法請求方式');
        }
        $controller = new SysuserController();
        $controller->delete($_POST['id'] ?? null);
        break;
        
    // 一般管理者profile
    case 'profile':
        $controller = new ProfileController();
        $controller->index();
        break;
    case 'profile_update_info':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $controller = new ProfileController();
        $controller->updateInfo();
        }
        break;
    case 'profile_update_password':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $controller = new ProfileController();
        $controller->updatePassword();
        }
        break;

    // 預設或錯誤頁
    default:
        echo "<h2>404 - Page Not Found</h2>";
        break;
}




?>