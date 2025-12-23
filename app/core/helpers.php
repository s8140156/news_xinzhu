<?php 

/**
 * 全域共用函式區
 * 放置多個 Controller / View 都會用到的邏輯
 * 例如：分類、封面圖、格式化日期、字串截斷等
 */

// require_once APP_PATH . '/config.php'; // 已在 index.php 載入，不需重複載入(先註記)
require_once __DIR__ . '/db.php';

// ===== Module constants =====
define('MODULE_ARTICLE', 1);
define('MODULE_CATEGORY', 2);
define('MODULE_SPONSORED', 3);
define('MODULE_PARTNER', 4);
define('MODULE_FOOTER', 5);
define('MODULE_SYSUSER', 6);


// 取得新聞分類對照表 id=>name
function getNewsCategoryMap($orderBy = 'id ASC') {
    $catDb = new DB('news_categories');
    $categories = [];
    foreach($catDb->all("1 ORDER BY $orderBy") as $cat) {
        $categories[$cat['id']] = $cat['name'];
    }
    return $categories;
}

// 取得完整分類清單
function getAllCategories($orderBy = 'sort ASC') {
    $catDb = new DB('news_categories');
    return $catDb->all("1 ORDER BY $orderBy");
}

// 取得文章封面圖片邏輯(可前後台共用)
function getCoverImage($article) {
    // Debug
    // print_r($article); 
    // exit;
    // cover_image先
    // if (!empty($article['cover_image'])) {
    //     $cover = $article['cover_image'];

    //     // 將 URL 換成相對路徑
    //     $relative = str_replace(BASE_URL, '', $cover);
    //     $relative = ltrim($relative, '/');

    //     // 加入 "news_xinzhu/public"（你的專案結構）
    //     $realPath = $_SERVER['DOCUMENT_ROOT'] . '/news_xinzhu/public/' . $relative;

    //     // Debug：印出確認
    //     // echo "Check real path: $realPath<br>";

    //     if (file_exists($realPath)) {
    //         return BASE_URL . '/' . $relative;
    //     }
    // }

    if(!empty($article['cover_image'])) {
        $relative = ltrim($article['cover_image'], '/');

        $filePath = str_replace('uploads/', '', $relative);

        $realPath = UPLOAD_PATH . '/' . $filePath;

        if(file_exists($realPath)) {
            return STATIC_URL . '/' . $relative;
        }
    }
    return STATIC_URL . '/assets/frontend/images/oops_cover.png';
}

// 前台取得焦點新聞最新文章
function getFocusArticle() {
    $dbCat = new DB('news_categories');
    $cat = $dbCat->all("is_focus = 1 LIMIT 1");

    if (!$cat) return null;

    $categoryId = $cat[0]['id'];

    $dbArt = new DB('articles');
    $rows = $dbArt->all(
        "category_id = ? AND status='published' ORDER BY publish_time DESC LIMIT 1",
        [$categoryId]
    );
    return $rows ? $rows[0] : null;
}

// 取得某分類最新文章(首頁卡片)
function getLatestArticleByCategory($categoryId) {
    $db = new DB('articles');
    $rows = $db->all(
        "category_id = ? AND status='published' ORDER BY publish_time DESC LIMIT 1",
        [$categoryId]
    );
    return $rows ? $rows[0] : null;
}

function getArticlesByCategory($categoryId) {
    $db = new DB('articles');
    $rows = $db->all(
        "category_id = ? AND status='published' ORDER BY publish_time DESC",
        [$categoryId]
    );
    return $rows ?: [];
}

/**
 * 修正上傳圖片的 EXIF 方向
 * 僅 JPEG 有 Orientation 標籤
 */
function fixImageOrientation($img, $tmpPath) {
    if (!function_exists('exif_read_data')) {
        return $img; // 若 PHP 未啟用 exif 擴展，直接返回原圖
    }
    $exif = @exif_read_data($tmpPath);
    if (!$exif || !isset($exif['Orientation'])) {
        return $img; // 無 EXIF 資料或無 Orientation 標籤，直接返回原圖
    }
    $orientation = $exif['Orientation'];

    switch ($orientation) {
        case 3: // 180°
            $img = imagerotate($img, 180, 0);
            break;
        case 6: // 右轉 → -90
            $img = imagerotate($img, -90, 0);
            break;
        case 8: // 左轉 → +90
            $img = imagerotate($img, 90, 0);
            break;
    }

    return $img;
}

//定義唯一權限判斷入口
function canView($moduleId) {
    if (!empty($_SESSION['is_super_admin'])) {
        return true;
    }
    return !empty($_SESSION['permissions'][$moduleId]['can_view']);
}

function canCreate($moduleId) {
    if (!empty($_SESSION['is_super_admin'])) {
        return true;
    }
    return !empty($_SESSION['permissions'][$moduleId]['can_create']);
}

function canEdit($moduleId) {
    if (!empty($_SESSION['is_super_admin'])) {
        return true;
    }
    return !empty($_SESSION['permissions'][$moduleId]['can_edit']);
}

function canDelete($moduleId) {
    if (!empty($_SESSION['is_super_admin'])) {
        return true;
    }
    return !empty($_SESSION['permissions'][$moduleId]['can_delete']);
}

// 改成403共用function
// function forbidden() {
//     http_response_code(403);
//     echo "403 Forbidden";
//     exit;
// }

function abort403($message = '你沒有權限存取此功能。') {
    http_response_code(403);

    $title = '403 權限不足';
    $message = $message;

    $content = APP_PATH . '/views/backend/errors/403.php';
    include APP_PATH . '/views/backend/layouts/main.php';
    exit;
}

function requirePermission($action, $moduleId) {

    switch ($action) {
        case 'view':
            if (!canView($moduleId)) {
                abort403('你沒有查看此功能的權限。');
            }
            break;

        case 'create':
            if (!canCreate($moduleId)) {
                abort403('你沒有新增此功能的權限。');
            }
            break;

        case 'edit':
            if (!canEdit($moduleId)) {
                abort403('你沒有編輯此功能的權限。');
            }
            break;

        case 'delete':
            if (!canDelete($moduleId)) {
                abort403('你沒有刪除此功能的權限。');
            }
            break;

        default:
            abort403();
    }
}

// 初始密碼信
function sendInitPasswordMail($email, $name, $password, $type='init') {
    $loginUrl = BASE_URL . '?page=login';

    if($type === 'forget_password') {
        $subject  = '【馨築生活後台】忘記密碼通知';

        $message = "
            親愛的 {$name} 您好：<br><br>

            您於系統中申請「忘記密碼」，系統已為您重新產生一組臨時密碼：<br><br>

            <b>{$password}</b><br><br>

            請使用此密碼登入後台，並依指示立即變更您的密碼。<br><br>
            👉 <a href='{$loginUrl}'>請前往後台登入</a><br><br>

            若您未曾申請忘記密碼，請儘速聯繫系統管理員。
        ";
    } else {
        $subject = '【馨築生活後台】管理者帳號啟用通知';
    
        $message = "
            {$name} 您好：<br><br>
    
            您已被建立為後台管理者帳號，請使用以下資訊登入系統：<br><br>
            <b>登入帳號：</b>{$email}<br>
            <b>初始密碼：</b>{$password}<br><br>
            請於首次登入後立即修改密碼，以確保帳號安全。<br><br>
            👉 <a href='{$loginUrl}'>請前往後台登入</a><br><br>
            若您未預期收到此信件，請忽略。
        ";

    }


    // $ch = curl_init('http://localhost/news_xinzhu/public/api/sendmail.php');
    $ch = curl_init(BASE_URL . '/api/sendmail.php');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'member_email' => $email,
        'member_name'  => $name,
        'subject'      => $subject,
        'body'         => $message,
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $logPath = ROOT_PATH . '/storage/mail.log';
    // 測試期間可以先 log
    file_put_contents(
        $logPath,
        date('Y-m-d H:i:s') . PHP_EOL .
        $response . PHP_EOL .
        str_repeat('-', 40) . PHP_EOL,
        FILE_APPEND
    );
}
















?>