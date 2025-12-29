<?php

require_once APP_PATH . '/core/db.php';
require_once APP_PATH . '/core/helpers.php';

class footerArticleController {
    public function index() {
        $db = new DB('footer_articles');
        $footers = $db->all('1 ORDER BY sort ASC');

        $content = APP_PATH . '/views/backend/footer_articles/index.php';
        include APP_PATH . '/views/backend/layouts/main.php';
    }

    public function create() {
        $mode = 'create';

        // 預設空文章結構(因為新增/編輯共用表單)
        $footerArticle = [
            'id' => '',
            'title' => '',
            'content' => '',
            'author' => '',
            'status' => '',
        ];

        $content = APP_PATH . '/views/backend/footer_articles/form.php';
        include APP_PATH . '/views/backend/layouts/main.php';
    }

    public function store() {
        $title = $_POST['title'] ?? '';
        $author = $_POST['author'] ?? '';
        $content = $_POST['editorContent'] ?? '';
        $content = str_replace(BASE_URL . '/', '', $content);

        if (empty($title) || empty($author) || empty($content)) {
            echo "<script>alert('請確認必填欄位是否完整');history.back();</script>";
            return;
        }

        $status = ($_POST['action'] ?? 'draft') === 'publish' ? 'published' : 'draft';

        $db = new DB('footer_articles');
        $sql = "SELECT COUNT(*) AS total FROM footer_articles";
        $count =$db->query($sql)[0]['total'] ?? 0;
        if($count >= 5) {
            echo "<script>alert('頁尾標籤最多只能新增5篇文章');history.back();</script>";
            exit;
        }
        $row = $db->query("SELECT MAX(sort) AS max_sort FROM footer_articles");
        $nextSort = ($row[0]['max_sort'] ?? 0) + 1;

        $db->insert([
            'title' => $title,
            'author' => $author,
            'content' => $content,
            'status' => $status,
            'views' => 0,
            'sort' => $nextSort,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $footerId = $db->getLastInsertId();

        // 搬移 temp 圖片
        $tempDir = UPLOAD_PATH . '/temp/';
        $targetDir = UPLOAD_PATH . "/footer_articles/content/{$footerId}/";
        if (is_dir($tempDir)) {
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
            foreach (glob($tempDir . '*') as $file) {
                rename($file, $targetDir . basename($file));
            }
        }

        // 修正 content 圖片路徑
        $content = str_replace(
            ['uploads/temp/', '/uploads/temp/'],
            "uploads/footer_articles/content/{$footerId}/",
            $content
        );

        // 解析連結
        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML('<?xml encoding="utf-8" ?>' . $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $linksArr = [];
        foreach ($doc->getElementsByTagName('a') as $a) {
            $href = $a->getAttribute('href');
            $text = trim($a->textContent);
            if ($href) {
                $linksArr[] = ['url' => $href, 'text' => $text];
            }
        }

        $db->update($footerId, [
            'content' => $content,
            'links' => json_encode($linksArr, JSON_UNESCAPED_UNICODE),
            'link_clicks' => json_encode(array_fill(0, count($linksArr), 0))
        ]);

        echo "<script>alert('頁尾標籤新增成功');window.location='?page=footer_index';</script>";
        exit;
    }

    // 處理 CKEditor 內文圖片上傳（頁尾標籤）
    public function footerImageUpload() {
        // 清除緩衝區
        if (function_exists('ob_get_level')) {
            while (ob_get_level()) ob_end_clean();
        }

        if (!isset($_FILES['upload']) || $_FILES['upload']['error'] !== UPLOAD_ERR_OK) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'uploaded' => 0,
                'error' => ['message' => '未接收到上傳檔案或發生錯誤']
            ]);
            return;
        }

        $file = $_FILES['upload'];
        $allowedExt  = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $allowedMime = ['image/jpg','image/jpeg','image/png','image/gif','image/webp'];

        $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $mime = mime_content_type($file['tmp_name']);

        if (!in_array($ext, $allowedExt) || !in_array($mime, $allowedMime)) {
            $msg = '不支援的檔案格式，僅允許 jpg/png/gif/webp';

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

        // footer 專用 id（新增時為 temp）
        $footerId = !empty($_GET['id']) ? $_GET['id'] : 'temp';

        // 上傳目錄
        if ($footerId !== 'temp') {
            $uploadDir = UPLOAD_PATH . "/footer_articles/content/{$footerId}/";
        } else {
            $uploadDir = UPLOAD_PATH . "/temp/";
        }

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName   = time() . '_' . uniqid() . '.' . $ext;
        $targetPath = $uploadDir . '/' . $fileName;

        // 建立圖片資源
        switch ($ext) {
            case 'jpg':
            case 'jpeg': $src = imagecreatefromjpeg($file['tmp_name']); break;
            case 'png':  $src = imagecreatefrompng($file['tmp_name']); break;
            case 'gif':  $src = imagecreatefromgif($file['tmp_name']); break;
            case 'webp': $src = imagecreatefromwebp($file['tmp_name']); break;
            default:     $src = null;
        }
        if (!$src) {
            echo json_encode(['uploaded' => 0, 'error' => ['message' => '讀取圖片失敗']]);
            return;
        }

        // 修正方向
        if ($ext === 'jpg' || $ext === 'jpeg') {
            $src = fixImageOrientation($src, $file['tmp_name']);
        }

        // 最大寬度 600px
        $width  = imagesx($src);
        $height = imagesy($src);
        if ($width > 600) {
            $ratio = $height / $width;
            $newW = 600;
            $newH = (int)($newW * $ratio);
            $dst = imagecreatetruecolor($newW, $newH);
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $width, $height);
            imagedestroy($src);
            $src = $dst;
        }

        // 輸出檔案
        switch ($ext) {
            case 'jpg':
            case 'jpeg': imagejpeg($src, $targetPath, 90); break;
            case 'png':  imagepng($src, $targetPath); break;
            case 'gif':  imagegif($src, $targetPath); break;
            case 'webp': imagewebp($src, $targetPath, 90); break;
        }
        imagedestroy($src);

        $fileUrl = ($footerId !== 'temp')
            ? UPLOAD_URL . "/footer_articles/content/{$footerId}/" . $fileName
            : UPLOAD_URL . "/temp/" . $fileName;

        if (isset($_GET['CKEditorFuncNum'])) {
            $funcNum = (int)$_GET['CKEditorFuncNum'];
            header('Content-Type: text/html; charset=utf-8');
            echo "<script>window.parent.CKEDITOR.tools.callFunction($funcNum, '" . addslashes($fileUrl) . "', '');</script>";
        }
    }

    public function edit($id) {
        $mode = 'edit';

        $db = new DB('footer_articles');
        $id = $_GET['id'] ?? null;
        $footerArticle = $db->find($id);

        $content = APP_PATH . '/views/backend/footer_articles/form.php';
        include APP_PATH . '/views/backend/layouts/main.php';
    }


    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            if (!$id) {
                echo "缺少頁尾標籤ID，無法更新";
                return;
            }
        }

        $db = new DB('footer_articles');
        $old = $db->find($id);

        // 接收資料
        $title   = $_POST['title'] ?? '';
        $author  = $_POST['author'] ?? '';
        $content = $_POST['editorContent'] ?? '';
        $content = str_replace(BASE_URL . '/', '', $content);

        if (empty($title) || empty($author) || empty($content)) {
            echo "<script>alert('請確認必填欄位是否完整');history.back();</script>";
            return;
        }

        // 狀態（只有 draft / published）
        $action = $_POST['action'] ?? null;
        if ($action === 'publish') {
            $status = 'published';
        } elseif ($action === 'draft') {
            $status = 'draft';
        } else {
            $status = $old['status'];
        }

        // === 圖片資料夾同步清理 ===
        $targetDir = UPLOAD_PATH . "/footer_articles/content/{$id}/";

        // 使用者主動刪除清單（若你 footer form 有）
        $deletedList = json_decode($_POST['deleted_images'] ?? '[]', true);
        foreach ($deletedList as $url) {
            $file = basename($url);
            $path = $targetDir . $file;
            if (is_file($path)) unlink($path);
        }

        // 自動清理：不在 content 中的圖片
        if (is_dir($targetDir)) {
            $files = glob($targetDir . '*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);
            foreach ($files as $file) {
                if (strpos($content, basename($file)) === false) {
                    unlink($file);
                }
            }
        }

        // 修正 temp 圖片路徑
        $content = str_replace(
            ['uploads/temp/', '/uploads/temp/'],
            "uploads/footer_articles/content/{$id}/",
            $content
        );

        // === 解析 <a> 標籤 ===
        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML('<?xml encoding="utf-8" ?>' . $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $newLinks = [];
        foreach ($doc->getElementsByTagName('a') as $a) {
            $href = $a->getAttribute('href');
            $text = trim($a->textContent);
            if ($href) {
                $newLinks[] = ['url' => $href, 'text' => $text];
            }
        }

        // === 同步 link_clicks（關鍵） ===
        $oldLinks       = json_decode($old['links'] ?? '[]', true);
        $oldClicks      = json_decode($old['link_clicks'] ?? '[]', true);
        $oldClickMap    = [];

        foreach ($oldLinks as $idx => $link) {
            if (isset($oldClicks[$idx])) {
                $oldClickMap[$link['url']] = $oldClicks[$idx];
            }
        }

        $newClicks = [];
        foreach ($newLinks as $link) {
            $newClicks[] = $oldClickMap[$link['url']] ?? 0;
        }

        // === 更新資料 ===
        $db->update($id, [
            'title'       => $title,
            'author'      => $author,
            'content'     => $content,
            'links'       => json_encode($newLinks, JSON_UNESCAPED_UNICODE),
            'link_clicks' => json_encode($newClicks),
            'status'      => $status,
            'updated_at'  => date('Y-m-d H:i:s')
        ]);

        echo "<script>alert('頁尾標籤更新成功！');window.location='?page=footer_index';</script>";
        exit;
    }

    public function delete() {
        $id = $_GET['id'] ?? null;
        if(!$id || !is_numeric($id)) {
            echo "<script>alert('缺少頁尾標籤文章ID 或 ID格式錯誤');history.back();</script>";
            return;
        }

        $db = new DB('footer_articles');
        $article = $db->find($id);

        if(!$article) {
            echo "<script>alert('找不到指定頁尾標籤文章，無法刪除');history.back();</script>";
            return;
        }

        // 同步清理 CKEditor 上傳圖片
        $contentDir = UPLOAD_PATH . "/footer_articles/content/{$id}/";
        if (is_dir($contentDir)) {
            // 刪除所有內文圖片檔案
            $files = glob($contentDir . "/*");
            foreach($files as $file) {
                if(is_file($file)) unlink($file); 
            }
            // 刪除資料夾
            rmdir($contentDir);
        }

        // 刪除資料庫記錄
        $deleted = $db->delete($id);

        $rows = $db->query(
            "SELECT id FROM footer_articles ORDER BY sort ASC, id ASC"
        );

        foreach ($rows as $index => $row) {
            $db->update($row['id'],[
                'sort' => $index + 1
            ]);
        }

        if ($deleted) {
            echo "<script>alert('文章已刪除成功！');window.location = '?page=footer_index';</script>";
        } else {
            echo "<script>alert('刪除失敗，請稍後再試');history.back();</script>";
        }

    }

    public function updateSort() {

        $data = json_decode(file_get_contents('php://input'), true);

        if (!is_array($data)) {
            echo json_encode(['success' => false]);
            return;
        }

        $db = new DB('footer_articles');

        foreach ($data as $item) {
            $db->update($item['id'], [
                'sort' => (int)$item['sort'],
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        echo json_encode(['success' => true]);
    }
}
