<?php

require_once APP_PATH . '/core/db.php';

class ArticleController {

    public function create() {
        //建立DB連線
        $db =new DB('news_categories');
        $categories = $db->all("1 ORDER BY sort ASC");

        $content = APP_PATH . '/views/backend/articles/create.php';
        include APP_PATH . '/views/backend/layouts/main.php';
    }

    public function store() {
        // 先確認文章狀態
        $action = $_POST['action'] ?? 'draft';
        switch($action) {
            case 'publish':
                $status = 'published';
                $publish_time = date('Y-m-d H:i:s'); // 立即發布
                break;
            case 'schedule':
                $status = 'scheduled';
                // 接收排程時間
                $date = $_POST['schedule_date'] ?? '';
                $time = $_POST['schedule_time'] ?? '';
                $publish_time = ($date && $time) ? $date . ' ' . $time . ':00' : null;
                break;
            default:
                $status = 'draft';
                $publish_time = null;
                break;
        }
        // 欄位接收
        $title = $_POST['title'] ?? '';
        $author = $_POST['author'] ?? '';
        $category_id = $_POST['category_id'] ?? '';
        $content = $_POST['editorContent'] ?? '';

        if(empty($category_id) || empty($author) || empty($title)) {
            echo "<script>alert('請確認必填欄位是否完整？');history.back();</script>";
            return;
        }
        // 封面圖片上傳(可選)
        $coverPath = null;

        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['cover_image'];
            $fileName = time() . '_' . basename($file['name']);

            // 使用絕對路徑，避免走錯層
            $coverDir = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/news_xinzhu/public/uploads/articles/cover/';

            // 若目錄不存在，建立目錄
            if (!is_dir($coverDir)) {
                mkdir($coverDir, 0777, true);
                // error_log('[封面上傳測試] 自動建立 cover 資料夾：' . $coverDir);
            }

            // 確認實際路徑
            // error_log('[封面上傳測試] PHP 實際嘗試寫入路徑：' . $coverDir);
            // error_log('[封面上傳測試] 該目錄是否存在？ → ' . (is_dir($coverDir) ? '是' : '否'));

            $targetPath = $coverDir . $fileName;
            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                $coverPath = BASE_URL . '/uploads/articles/cover/' . $fileName;
                // error_log('[封面上傳成功] 實際搬移到：' . $targetPath);
            } else {
                // error_log('[封面上傳失敗] 無法搬移到：' . $targetPath);
            }
        }

        // 從CKEditor內容解析圖片與圖說
        $doc = new DOMDocument();
        libxml_use_internal_errors(true); // 避免HTML5標籤報錯
        // 使用flags避免DOM自動補html, body
        $doc->loadHTML('<?xml encoding="utf-8" ?>' . $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $imagesArr = [];

        // 確認有figure包的(img+caption)
        $figures = $doc->getElementsByTagName('figure');

        foreach($figures as $figure) {
            $imgTag = $figure->getElementsByTagName('img')->item(0);
            $captionTag = $figure->getElementsByTagName('figcaption')->item(0);

            if($imgTag instanceof DOMElement) {
                $url = $imgTag->getAttribute('src') ?: '';
                $caption = ($captionTag instanceof DOMElement) ? trim($captionTag->textContent) : '';
                if($url !=='') {
                    $imagesArr[] = [
                        'url' => $url,
                        'caption' => $caption
                    ];
                    if(!$coverPath) {
                        $coverPath = $url;
                    }
                }
            }
        }
        // 確認figure下的<img>
        $allImgs = $doc->getElementsByTagName('img');
        foreach ($allImgs as $img) {
            $url = $img->getAttribute('src') ?: '';
            if ($url !== '') {
                $isAlreadyCaptured = false;
                foreach ($imagesArr as $imgData) {
                    if ($imgData['url'] === $url) {
                        $isAlreadyCaptured = true;
                        break;
                    }
                }
                if (!$isAlreadyCaptured) {
                    $imagesArr[] = ['url' => $url, 'caption' => ''];
                    if (!$coverPath) {
                        $coverPath = $url;
                    }
                }
            }
        }
        $imagesJson = json_encode($imagesArr, JSON_UNESCAPED_UNICODE);
        
        $db = new DB('articles');
        // var_dump([
        //     'title' => $title,
        //     'author' => $author,
        //     'category_id' => $category_id,
        //     'content' => mb_substr($content, 0, 100),
        //     'images' => $coverPath,
        //     'status' => $status,
        //     ]); exit;

        $db->insert([
            'title' => $title,
            'author' => $author,
            'category_id' => $category_id,
            'content' => $content,
            'images' => $imagesJson,
            'cover_image' => $coverPath,
            'status' => $status,
            'publish_time' => $publish_time,
            'views' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        // header('Location: ' . BASE_URL . '/?page=article_index');
        echo "測試成功 文章已新增！";
        exit;        
    }

    // 處理CKEditor內文圖片上傳
    public function imageUpload() {
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

        $file = $_FILES['upload'];
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if(!in_array($ext, $allowed)) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'uploaded' => 0,
                'error' => ['message' => '不支援的檔案格式，僅允許jpg/png/gif/webp']
            ]);
            return;
        }

        $allowedTypes = ['image/jpg','image/jpeg','image/png','image/gif','image/webp'];
        $mime = mime_content_type($file['tmp_name']);
        if(!in_array($mime, $allowedTypes)) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'uploaded' => 0,
                'error' => ['message' => '不支援的圖片格式']
            ]);
            return;
        }

        $uploadDir = APP_PATH . '/../public/uploads/articles/content';
        if(!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = time() . '_' . uniqid() . '.' . $ext;
        $targetPath = $uploadDir . '/' . $fileName;

        if(move_uploaded_file($file['tmp_name'], $targetPath)) {
            $fileUrl = BASE_URL . "/uploads/articles/content/" . $fileName;

            // 若有 CKEditorFuncNum，回傳舊協定（對話框上傳）
            if (isset($_GET['CKEditorFuncNum'])) {
                $funcNum = (int)$_GET['CKEditorFuncNum'];
                header('Content-Type: text/html; charset=utf-8');
                echo "<script>window.parent.CKEDITOR.tools.callFunction($funcNum, '".addslashes($fileUrl)."', '');</script>";
            } else {
                // 給 JSON 模式（貼上/拖曳上傳）
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'uploaded'  => 1,
                    'fileName'  => $fileName,
                    'url'       => $fileUrl,
                ]);
            }
        } else {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'uploaded' => 0,
                'error' => ['message' => '圖片上傳失敗，請確認權限或路徑設定']
            ]);
        }
    }










}

?>