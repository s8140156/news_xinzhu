<?php

require_once APP_PATH . '/core/db.php';
require_once APP_PATH . '/core/helpers.php';
// 讀取設定檔（包含 UPLOAD_PATH / UPLOAD_URL）

class ArticleController {

    public function index() {
        //建立DB連線及檢查排程發佈及更新
        $db =new DB('articles');

        // 自動排程：發布排程到期文章(測試用)
        // $this->checkAndPublishScheduledArticles();

        // 讀取搜尋與排序條件
        $category = $_GET['category'] ?? '';
        $start_date = $_GET['start_date'] ?? '';
        $end_date = $_GET['end_date'] ?? '';
        $keyword = $_GET['keyword'] ?? '';
        $sort = $_GET['sort_by'] ?? null;
        $status = $_GET['status'] ?? 'all';

        // sql條件
        $where = '1'; // sql where 1 預設條件
        $params = [];

        // 搜尋邏輯
        //狀態：允許白名單
        $allow = ['updated_desc', 'publish_desc', 'schedule_asc'];
        if(!in_array($sort, $allow, true)) $sort = null;

        switch($status) {
            case 'scheduled':
                if ($sort === null) $sort = 'schedule_asc';
                break;
            case 'published':
                if ($sort === null) $sort = 'publish_desc';
                break;
            default:
                if ($sort === null || $sort === 'schedule_asc') $sort = 'updated_desc';
                break;
        }

        // 依sort_by組ORDER BY
        if(!empty($status) && $status !== 'all') {
            $where .= " AND status = :status";
            $params[':status'] = $status;
        }

        // 類別
        if($category !== "" && $category !== null) {
                // 篩選未分類0
            if ($category === "0") {
                $where .= " AND (category_id = 0 OR category_id IS NULL)";
            }else {
                // 一般分類
                $where .= " AND category_id = :category_id";
                $params[':category_id'] = $category;
            }
        }

        // 日期區間
        if(!empty($start_date)) {
            $where .= " AND DATE(updated_at) >= :start_date";
            $params[':start_date'] = $start_date;
        }
        if(!empty($end_date)) {
            $where .= " AND DATE(updated_at) <= :end_date";
            $params[':end_date'] = $end_date;
        }
        // 標題關鍵字
        if(!empty($keyword)) {
            $where .= " AND title LIKE :keyword";
            $params[':keyword'] = "%{$keyword}%";
        }
        // 排序邏輯
        switch($sort) {
            case 'publish_desc':
                $order = "publish_time DESC";
                break;
            case 'schedule_asc':
                $order = "publish_time ASC";
                break;
            default:
                $order = "updated_at DESC";
                break;
        }
        // 智能群組排序
        if ($status === 'all') {
            // 沒有指定狀態篩選 → 依狀態群組顯示
            $order = "FIELD(status, 'published', 'scheduled', 'draft') ASC, " . $order;
        }
        // echo "<pre>目前排序條件：$order</pre>";

        $articles = $db->all("$where ORDER BY $order", $params);
        // print_r($articles);

        // 撈新聞分類對照
        $categories = $this->getCategoryMap('sort ASC');

        $content = APP_PATH . '/views/backend/articles/index.php';
        include APP_PATH . '/views/backend/layouts/main.php';
    }

    public function create() {
        $mode = 'create';

        //建立DB連線
        $categories = $this->getCategoryMap('sort ASC');

        // 預設空文章結構(因為新增/編輯共用表單)
        $article = [
            'id' => '',
            'category_id' => '',
            'title' => '',
            'content' => '',
            'author' => '',
            'cover_image' => '',
            'status' => '',
            'publish_time' => ''
        ];

        // 預設空排程時間(因為新增/編輯共用表單)
        $publishDate = '';
        $publishTime = '';

        $content = APP_PATH . '/views/backend/articles/form.php';
        include APP_PATH . '/views/backend/layouts/main.php';
    }

    public function store() {
        // 欄位接收
        $title = $_POST['title'] ?? '';
        $author = $_POST['author'] ?? '';
        $category_id = $_POST['category_id'] ?? '';
        $content = $_POST['editorContent'] ?? '';
        $content = str_replace(BASE_URL . '/', '', $content); // 移除完整網址 content只存相對路徑

        $date = $_POST['schedule_date'] ?? '';
        $time = $_POST['schedule_time'] ?? '';

        if(empty($category_id) || empty($author) || empty($title) || empty($content)) {
            echo "<script>alert('請確認必填欄位是否完整？');history.back();</script>";
            return;
        }
        // 確認文章狀態
        $action = $_POST['action'] ?? 'draft';
        switch($action) {
            case 'publish':
                $status = 'published';
                $publish_time = date('Y-m-d H:i:s'); // 立即發布
                break;
            case 'schedule':
                $status = 'scheduled';
                // 排程防呆
                if(empty($date)){
                    echo "<script>alert('請設定完整的排程日期與時間');history.back();</script>";
                    return;
                }
                if(empty($time)){
                    $time = '00:00';
                }
                $publish_time = $date . ' ' . $time . ':00';
                break;
            default:
                $status = 'draft';
                $publish_time = null;
                break;
        }

        // 先插入文章主體資料(圖片待取得id後更新)
        $db = new DB('articles');
        $db->insert([
            'title' => $title,
            'author' => $author,
            'category_id' => $category_id,
            'content' => $content,
            'status' => $status,
            'publish_time' => $publish_time,
            'views' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // 取得剛新增的文章id
        $articleId = $db->getLastInsertId();

        // 封面圖片上傳(可選)
        $coverPath = null;
        if (!empty($_FILES['cover_image']['tmp_name']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['cover_image'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $fileName = time() . '_' . uniqid() . '.' . $ext;
            // $coverDir = APP_PATH . '/../public/uploads/articles/cover/';
            $coverDir = UPLOAD_PATH . '/articles/cover/';
            if (!is_dir($coverDir)) mkdir($coverDir, 0777, true);
            $targetPath = $coverDir . $fileName;
            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                $coverPath = "uploads/articles/cover/{$fileName}";
            }
        }

        // 搬移圖片資料夾temp->content{id}
        // $tempDir = APP_PATH . '/../public/uploads/temp/';
        // $targetDir = APP_PATH . "/../public/uploads/articles/content/{$articleId}/";
        $tempDir = UPLOAD_PATH . '/temp/';
        $targetDir = UPLOAD_PATH . "/articles/content/{$articleId}/";

        if(is_dir($tempDir)) {
            if(!is_dir($targetDir)) mkdir ($targetDir, 0777, true);

            // 先搬移檔案
            $files = glob($tempDir . '*');
            foreach($files as $filePath) {
                $destPath = $targetDir . basename($filePath);
                rename($filePath, $destPath);
            }
            // 清空temp資料夾
            $files = glob($tempDir . '*');
            foreach($files as $filePath) {
                if(is_file($filePath)) unlink($filePath);
            }
        }

        // 更新內文中的圖片路徑
        $content = str_replace('/uploads/temp/', "uploads/articles/content/{$articleId}/", $content);
        $content = str_replace('uploads/temp/', "uploads/articles/content/{$articleId}/", $content);
        

        // 從CKEditor內容解析圖片與圖說
        $doc = new DOMDocument();
        libxml_use_internal_errors(true); // 避免HTML5標籤報錯
        // 使用flags避免DOM自動補html, body
        $doc->loadHTML('<?xml encoding="utf-8" ?>' . $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        // === Step 1: 抓取所有圖片（不論是否 figure 包住）===
        $imagesArr = [];
        $allImgs = $doc->getElementsByTagName('img');
        $firstImageSrc = null;
        foreach ($allImgs as $img) {
            $url = $img->getAttribute('src') ?: '';
            if ($url !== '' && !$firstImageSrc) {
                $firstImageSrc = $url; // 第一張圖片
            }
        }

        // === Step 2: 抓取有圖說的圖片（用於 images JSON）===
        $figures = $doc->getElementsByTagName('figure');
        foreach ($figures as $figure) {
            $imgTag = $figure->getElementsByTagName('img')->item(0);
            $captionTag = $figure->getElementsByTagName('figcaption')->item(0);

            if ($imgTag instanceof DOMElement) {
                $url = $imgTag->getAttribute('src') ?: '';
                $caption = ($captionTag instanceof DOMElement) ? trim($captionTag->textContent) : '';
                if ($url !== '') {
                    $imagesArr[] = [
                        'url' => $url,
                        'caption' => $caption
                    ];
                }
            }
        }

        // === Step 3: 若沒有 figure，也抓剩下未重複的圖片（補齊 JSON）===
        foreach ($allImgs as $img) {
            $url = $img->getAttribute('src') ?: '';
            if ($url !== '') {
                $exists = false;
                foreach ($imagesArr as $imgData) {
                    if ($imgData['url'] === $url) {
                        $exists = true;
                        break;
                    }
                }
                if (!$exists) {
                    $imagesArr[] = ['url' => $url, 'caption' => ''];
                }
            }
        }

        // === Step 4: 確定封面圖片 ===
        // 若有上傳封面圖，維持上傳的；否則取內文第一張圖

        if ($firstImageSrc) {
            $firstImageSrc = str_replace('/uploads/temp/', "uploads/articles/content/{$articleId}/", $firstImageSrc);
            $firstImageSrc = str_replace('uploads/temp/', "uploads/articles/content/{$articleId}/", $firstImageSrc);
        }
        if (!$coverPath && $firstImageSrc) {
            $coverPath = $firstImageSrc;
        }
        $imagesJson = json_encode($imagesArr, JSON_UNESCAPED_UNICODE);

        // 解析CKEditor<a>標籤
        $linksArr = [];
        foreach($doc->getElementsByTagName('a') as $a) {
            $href = $a->getAttribute('href');
            $text = trim($a->textContent);
            if($href) {
                $linksArr[] = [
                    'url' => $href,
                    'text' => $text
                ];
            }
        }
        $linksJson = json_encode($linksArr, JSON_UNESCAPED_UNICODE);

        // 最後更新文章圖片與封面及內文路徑
        $db->update($articleId, [
            'images' => $imagesJson,
            'links' => $linksJson,
            'cover_image' => $coverPath,
            'content' => $content
        ]);
        echo "<script>alert('文章新增成功！');window.location = '?page=article_index';</script>";
        exit;
    }

    // 處理CKEditor內文圖片上傳
    public function imageUpload() {

        // global $UPLOAD_PATH, $UPLOAD_URL;   // <-- 讓這兩個變數變成全域使用
        // error_log("=== DEBUG START ===");
        // error_log("UPLOAD_PATH: " . $UPLOAD_PATH);
        // error_log("UPLOAD_URL: " . $UPLOAD_URL);
        // error_log("APP_PATH: " . APP_PATH);
        // error_log("Current __DIR__: " . __DIR__);

        // 清除緩衝區，避免干擾回傳
        if (function_exists('ob_get_level')) while (ob_get_level()) ob_end_clean();

        if(!isset($_FILES['upload']) || $_FILES['upload']['error'] !== UPLOAD_ERR_OK) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'uploaded' => 0,
                'error' => ['message' => '未接收到上傳檔案或發生錯誤']
            ]);
            return;
        }
        // 檢查副檔名、MIME類型
        $file = $_FILES['upload'];
        $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $allowedMime = ['image/jpg','image/jpeg','image/png','image/gif','image/webp'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $mime = mime_content_type($file['tmp_name']);

        // === [2] 檔案格式驗證 ===
        if(!in_array($ext, $allowedExt) || !in_array($mime, $allowedMime)) {
            $msg = '不支援的檔案格式，僅允許 jpg/png/gif/webp';

            // CKEditor4 iframe 模式
            if (isset($_GET['CKEditorFuncNum'])) {
                $funcNum = (int)$_GET['CKEditorFuncNum'];
                header('Content-Type: text/html; charset=utf-8');
                echo "<script>alert('{$msg}');</script>";
                echo "<script>window.parent.CKEDITOR.tools.callFunction($funcNum, '', '');</script>";
            } else {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['uploaded' => 0, 'error' => ['message' => $msg]]);
            }
            return;
        }


        // 在編輯時判斷是哪篇文章(新增時為temp)
        $articleId = !empty($_GET['id']) ? $_GET['id'] : 'temp';
        // error_log("[UPLOAD DEBUG] APP_PATH-2=" . APP_PATH);
        // 決定目錄路徑
        if($articleId !== 'temp') {
            $uploadDir = UPLOAD_PATH . "/articles/content/{$articleId}/";
        }else {
            $uploadDir = UPLOAD_PATH . "/temp/";            
        }
        // error_log("articleId = " . $articleId);
        // error_log("uploadDir (final) = " . $uploadDir);

        // if(!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        // error_log(">>> [DEBUG] imageUpload() called, upload to $uploadDir");

        if (!is_dir($uploadDir)) {
            // error_log("mkdir try => " . $uploadDir);
            mkdir($uploadDir, 0777, true);
            // error_log("mkdir result => " . (is_dir($uploadDir) ? "SUCCESS" : "FAIL"));
        } else {
            error_log("Dir already exists: " . $uploadDir);
        }

        // 產生檔名與存擋
        $fileName = time() . '_' . uniqid() . '.' . $ext;
        $targetPath = $uploadDir . '/' . $fileName;

        // 新增圖片壓縮(最大寬度600px)
        $imgInfo = @getimagesize($file['tmp_name']);
        if ($imgInfo) {
            $width = $imgInfo[0];
            $height = $imgInfo[1];
            $maxWidth = 600;

            if ($width > $maxWidth) {
                $ratio = $height / $width;
                $newWidth = $maxWidth;
                $newHeight = (int)($newWidth * $ratio);

                switch ($ext) {
                    case 'jpg':
                    case 'jpeg':
                        $src = imagecreatefromjpeg($file['tmp_name']);
                        break;
                    case 'png':
                        $src = imagecreatefrompng($file['tmp_name']);
                        break;
                    case 'gif':
                        $src = imagecreatefromgif($file['tmp_name']);
                        break;
                    case 'webp':
                        $src = imagecreatefromwebp($file['tmp_name']);
                        break;
                    default:
                        $src = null;
                        break;
                }

                if ($src) {
                    $dst = imagecreatetruecolor($newWidth, $newHeight);
                    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

                    switch ($ext) {
                        case 'jpg':
                        case 'jpeg':
                            imagejpeg($dst, $targetPath, 85);
                            break;
                        case 'png':
                            imagepng($dst, $targetPath);
                            break;
                        case 'gif':
                            imagegif($dst, $targetPath);
                            break;
                        case 'webp':
                            imagewebp($dst, $targetPath, 85);
                            break;
                    }

                    imagedestroy($src);
                    imagedestroy($dst);
                    $resized = true;
                }
            }
        }

        // 若沒壓縮過，才用一般搬移
        if (empty($resized)) {
            $moved = move_uploaded_file($file['tmp_name'], $targetPath);
        }else {
            $moved = true;
        }
        // 新增圖片壓縮(最大寬度600px) end

        if($moved) {
            $fileUrl = ($articleId !== 'temp') 
                ? UPLOAD_URL . "/articles/content/{$articleId}/" . $fileName
                : UPLOAD_URL . "/temp/" . $fileName;

            // 若有 CKEditorFuncNum，回傳舊協定（對話框上傳）
            if (isset($_GET['CKEditorFuncNum'])) {
                $funcNum = (int)$_GET['CKEditorFuncNum'];
                header('Content-Type: text/html; charset=utf-8');
                echo "<script>window.parent.CKEDITOR.tools.callFunction($funcNum, '" . addslashes($fileUrl) . "', '');</script>";
            } 
        } else {
            // error_log("move_uploaded_file FAILED");
            // error_log("tmp_name: " . $file['tmp_name']);
            // error_log("targetPath: " . $targetPath);
            // error_log("is_writable(uploadDir)? " . (is_writable($uploadDir) ? "YES" : "NO"));
            // error_log("is_writable(targetPath dir)? " . (is_writable(dirname($targetPath)) ? "YES" : "NO"));
            
            $msg = '圖片上傳失敗，請確認權限或路徑設定';
            if (isset($_GET['CKEditorFuncNum'])) {
                $funcNum = (int)$_GET['CKEditorFuncNum'];
                header('Content-Type: text/html; charset=utf-8');
                echo "<script>alert('{$msg}');</script>";
                echo "<script>window.parent.CKEDITOR.tools.callFunction($funcNum, '', '');</script>";
            }
        }
    }

    /**
     * 編輯文章
     * @param int $id 文章 ID
     */

    public function edit($id) {
        $mode = 'edit';

        $db = new DB('articles');
        $id = $_GET['id'] ?? null;
        $article = $db->find($id);

        // 拆分排程時間
        $publishDate = '';
        $publishTime = '';
        if(!empty($article['publish_time'])) {
            $dt = new DateTime($article['publish_time']);
            $publishDate = $dt->format('Y-m-d');
            $publishTime = $dt->format('H:i');
        }

        $categories = $this->getCategoryMap('sort ASC');

        $content = APP_PATH . '/views/backend/articles/form.php';
        include APP_PATH . '/views/backend/layouts/main.php';
    }

    public function update($id) {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'] ?? null;
                if(!$id) {
                    echo "缺少文章ID，無法更新";
                    return;
                }
        }

        // 取資料庫資料
        $db = new DB('articles');
        $oldArticle = $db->find($id);

        // 接收新資料
        $title = $_POST['title'] ?? '';
        $category_id = $_POST['category_id'] ?? null;
        $author = $_POST['author'] ?? '';
        $content = $_POST['editorContent'] ?? '';
        $content = str_replace(BASE_URL . '/', '', $content); // 移除完整網址 content只存相對路徑

        $date = $_POST['schedule_date'] ?? '';
        $time = $_POST['schedule_time'] ?? '';

        $action = $_POST['action'] ?? null;
        // 狀態與發布時間
        if($action) {
            switch($action) {
                case 'publish':
                    $status = 'published';
                    $publish_time = date('Y-m-d H:i:s');
                    break;
                case 'schedule':
                    $status = 'scheduled';
                    // 排程防呆
                    if(empty($date)){
                        echo "<script>alert('請設定完整的排程日期與時間');history.back();</script>";
                        return;
                    }
                    if(empty($time)){
                        $time = '00:00';
                    }
                    $publish_time = $date . ' ' . $time . ':00';
                    break;
                case 'draft':
                    $status = 'draft';
                    $publish_time = null;
                    break;
            }
        } else {
            $status = $oldArticle['status'];
            $publish_time = $oldArticle['publish_time'];
        }

        // 封面圖片上傳處理
        $cover_image = $oldArticle['cover_image']; // 預設用舊圖
        if(!empty($_FILES['cover_image']['tmp_name']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['cover_image'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $fileName = time() . '_' . uniqid() . '.' . $ext;
            // $uploadDir = APP_PATH . '/../public/uploads/articles/cover/';
            $uploadDir = UPLOAD_PATH . '/articles/cover/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $targetPath = $uploadDir . $fileName;

            if(move_uploaded_file($_FILES['cover_image']['tmp_name'], $targetPath)) {
                $cover_image = "/uploads/articles/cover/" . $fileName;
            }
        }

        // 圖片資料夾處理
        // $targetDir = APP_PATH . "/../public/uploads/articles/content/{$id}/";
        $targetDir = UPLOAD_PATH . "/articles/content/{$id}/";
        $deletedList = json_decode($_POST['deleted_images'] ?? '[]', true);

        // 實體刪除清單
        foreach($deletedList as $url) {
            $file = basename($url);
            $path = $targetDir . $file;
            if(is_file($path)) unlink($path);
        }

        // 同步清理：刪除未出現在內容中的圖片
        $existingFiles = glob($targetDir . '*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);
        foreach ($existingFiles as $file) {
            $filename = basename($file);
            if (strpos($content, $filename) === false) {
                unlink($file); // 若該檔案已不在編輯器內文中 → 刪除
            }
        }

        $content = str_replace('/uploads/temp/', "uploads/articles/content/{$id}/", $content);
        $content = str_replace('uploads/temp/', "uploads/articles/content/{$id}/", $content);

        // 從CKEditor內容解析圖片與圖說
        $doc = new DOMDocument();
        libxml_use_internal_errors(true); // 避免HTML5標籤報錯
        // 使用flags避免DOM自動補html, body
        $doc->loadHTML('<?xml encoding="utf-8" ?>' . $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        // 解析CKEditor<a>標籤
        $linksArr = [];
        foreach($doc->getElementsByTagName('a') as $a) {
            $href = $a->getAttribute('href');
            $text = trim($a->textContent);
            if($href) {
                $linksArr[] = [
                    'url' => $href,
                    'text' => $text
                    ];
            }
        }
        $linksJson = json_encode($linksArr, JSON_UNESCAPED_UNICODE);

        // 更新資料表
        $db->update($id, [
            'title' => $title,
            'author' => $author,
            'category_id' => $category_id,
            'cover_image' => $cover_image,
            'content' => $content,
            'links' => $linksJson,
            'status' => $status,
            'publish_time' => $publish_time,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // 最後安全檢查（可選)
        // 若總張數仍超過5，可紀錄log以追蹤（不影響前端）
        $allFiles = glob($targetDir . '*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);
        if (count($allFiles) > 5) {
            error_log("[警告] 文章ID {$id} 圖片數量超過 5 張 (" . count($allFiles) . " 張)");
        }
        echo "<script>alert('文章更新成功！');window.location = '?page=article_index';</script>";
    }

    public function delete($id) {
        $id = $_GET['id'] ?? null;
        if(!$id || !is_numeric($id)) {
            echo "<script>alert('缺少文章ID 或 ID格式錯誤');history.back();</script>";
            return;
        }

        $db = new DB('articles');
        $article = $db->find($id);

        if(!$article) {
            echo "<script>alert('找不到指定文章，無法刪除');history.back();</script>";
            return;
        }
        // 可選：刪除封面圖片檔案（若存在）
        if (!empty($article['cover_image'])) {
            // 將 URL 轉為實際路徑
            $coverPath = str_replace(BASE_URL, rtrim($_SERVER['DOCUMENT_ROOT'], '/'), $article['cover_image']);

            if (file_exists($coverPath)) {
                unlink($coverPath);
                // error_log("🗑 已刪除封面圖片：" . $coverPath);
            }
        }

        // （可選）若要同步清理 CKEditor 上傳圖片
        // 可額外解析 content 或 images 欄位內的圖片路徑後逐一刪除
        if (!empty($article['images'])) {
            $images = json_decode($article['images'], true);
            foreach ($images as $img) {
                $imgPath = str_replace(BASE_URL, rtrim($_SERVER['DOCUMENT_ROOT'], '/'), $img['url']);
                if (file_exists($imgPath)) unlink($imgPath);
            }
        }

        // 刪除資料庫記錄
        $deleted = $db->delete($id);

        if ($deleted) {
            echo "<script>alert('文章已刪除成功！');window.location = '?page=article_index';</script>";
        } else {
            echo "<script>alert('刪除失敗，請稍後再試');history.back();</script>";
        }
    }

    private function getCategoryMap($orderBy = 'id ASC') {
        $catDb = new DB('news_categories');
        $categories = [];
        foreach ($catDb->all("1 ORDER BY $orderBy") as $cat) {
            $categories[$cat['id']] = $cat['name'];
        }
        return $categories;
    }

    private function checkAndPublishScheduledArticles() {
        $db = new DB('articles');
        $db->exec("
            UPDATE articles
            SET status = 'published'
            WHERE status = 'scheduled'
            AND publish_time <= NOW()");
    }

    private function autoCleanOldArticles() {
        $db = new DB('articles');
        // 刪掉「已發布」且 publish_time 超過半年
        $sql1 = "DELETE FROM articles
                WHERE status = 'published'
                AND publish_time < DATE_SUB(NOW(), INTERVAL 6 MONTH)";
        $db->exec($sql1);

        // 刪掉「草稿」且 updated_at 超過半年
        $sql2 = "DELETE FROM articles
                WHERE status = 'draft'
                AND updated_at < DATE_SUB(NOW(), INTERVAL 6 MONTH)";
        $db->exec($sql2);

    }

    private function cleanUnusedImages($id, $content) {
        $dir = APP_PATH . "/../public/uploads/articles/content/{$id}/";
        $imgsInContent = [];
        preg_match_all('/<img[^>]+src="([^">]+)"/i', $content, $matches);
        if (!empty($matches[1])) $imgsInContent = $matches[1];

        foreach (glob($dir . '*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE) as $imgPath) {
            $url = BASE_URL . "/uploads/articles/content/{$id}/" . basename($imgPath);
            if (!in_array($url, $imgsInContent)) unlink($imgPath);
        }
    }






}



?>
