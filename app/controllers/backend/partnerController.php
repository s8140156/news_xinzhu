<?php

require_once APP_PATH . '/core/db.php';
require_once APP_PATH . '/core/helpers.php';

class PartnerController {

    public function index() {

        $db = new DB('partners');
        $partners = $db->all('1 ORDER BY sort ASC');
        // var_dump($partners);
        // exit;

        $content = APP_PATH . '/views/backend/partners/index.php';
        include APP_PATH . '/views/backend/layouts/main.php';

    }

    public function store() {
        // echo '<pre>';
        // var_dump($_FILES);
        // exit;

        $links = $_POST['link_url'] ?? [];
        $ids = $_POST['id'] ?? [];
        $sorts = $_POST['sort'] ?? [];

        $db = new DB('partners');

        // 先處理刪除
        if(!empty($_POST['delete_ids'])) {
            foreach($_POST['delete_ids'] as $deleteId) {
                // 刪圖片資料夾
                $dir = UPLOAD_PATH . '/partners/' . $deleteId;
                if(is_dir($dir)) {
                    array_map('unlink', glob($dir . '/*'));
                    rmdir($dir);
                }
                $db->delete($deleteId);
            }
        }

        foreach($ids as $i=>$id) {
            if(empty($links[$i])) {
                continue;
            }
            // sort防呆
            $sortValue = (int)($sorts[$i] ?? 0);
            if($sortValue <=0) {
                $sortValue = $i + 1;
            }

            $now = date('Y-m-d H:i:s');
            // 啟用時間
            $startAt = !empty($_POST['start_at'][$i])
                ? $_POST['start_at'][$i]
                : $now;
            // 停用
            $endAt = !empty($_POST['end_at'][$i])
                ? $_POST['end_at'][$i]
                : null;
            $data = [
                'sort' => $sortValue,
                'start_at' => $startAt, 
                'end_at' => $endAt, 
                'link_url' => trim($links[$i]),
                'updated_at' => $now,
            ];
            // 新增/更新
            if(empty($id)) {
                $data['created_at'] = $now;
                $newId = $db->insert($data); // 先得到新增id 要用在新增圖片資料夾by id

                if (!empty($_FILES['image']['name'][$i]) 
                    && $_FILES['image']['error'][$i] === UPLOAD_ERR_OK) {

                    $uploadDir = UPLOAD_PATH . '/partners/' . $newId;

                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }

                    // 先刪舊 logo（不管副檔名）
                    foreach (glob($uploadDir . '/logo.*') as $oldFile) {
                        unlink($oldFile);
                    }

                    $ext = pathinfo($_FILES['image']['name'][$i], PATHINFO_EXTENSION);
                    $filename = 'logo.' . strtolower($ext);
                    $targetPath = $uploadDir . '/' . $filename;

                    move_uploaded_file($_FILES['image']['tmp_name'][$i], $targetPath);

                    $db->update($newId, [
                        'image' => 'uploads/partners/' . $newId . '/' . $filename,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            } else {
                $db->update($id, $data);
                // ===== 編輯時換圖 =====
                if (!empty($_FILES['image']['name'][$i]) 
                    && $_FILES['image']['error'][$i] === UPLOAD_ERR_OK) {

                    $uploadDir = UPLOAD_PATH . '/partners/' . $id;

                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }

                    // 先刪舊 logo（不管副檔名）
                    foreach (glob($uploadDir . '/logo.*') as $oldFile) {
                        unlink($oldFile);
                    }

                    $ext = pathinfo($_FILES['image']['name'][$i], PATHINFO_EXTENSION);
                    $filename = 'logo.' . strtolower($ext);
                    $targetPath = $uploadDir . '/' . $filename;

                    move_uploaded_file($_FILES['image']['tmp_name'][$i], $targetPath);

                    $db->update($id, [
                        'image' => 'uploads/partners/' . $id . '/' . $filename,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
        }
        echo "<script>alert('更新成功！');window.location = '?page=partner_index';</script>";
    }

}