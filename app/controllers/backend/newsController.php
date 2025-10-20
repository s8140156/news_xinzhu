<?php

require_once APP_PATH . '/core/db.php';

class NewsController {

    public function index() {
        //建立DB連線
        $db =new DB('news_categories');
        $categories = $db->all("1 ORDER BY `is_focus` DESC, `sort` ASC");
        // $categories = $db->all('', 'ORDER BY sort ASC');

        //指定頁面
        $content = APP_PATH . '/views/backend/news_categories/index.php';
        //將資料傳給view
        include APP_PATH . '/views/backend/layouts/main.php';
    }

    // 處理新增/更新/刪除
    public function store() {
            echo "<pre>";
            print_r($_POST);
            echo "</pre>";
            // exit;
        if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['act'] === 'addCategory') {
            $ids = $_POST['id'] ?? [];
            $names = $_POST['name'] ?? [];
            $sorts = $_POST['sort'] ?? [];
            $delete_ids = $_POST['delete_ids'] ?? [];
            $db = new DB('news_categories');

            // 先處理刪除
            foreach($delete_ids as $delId) {
                if(is_numeric($delId)) {
                    $db->delete($delId);
                }
            }

            foreach($names as $index => $name ){
                $id = $ids[$index] ?? '';
                $name = trim($names[$index] ?? '');
                $sort = $sorts[$index] ?? $index +1;

                // 跳過空值與未變更
                if($name === '') continue;

                if(is_numeric($id) && $name !== '') {
                    // 更新
                    $db->update($id,[
                        'name' => $name,
                        'sort' => $sort,
                        'updated_at' => date('Y-m-d H:i:s')]);
                // var_dump($ok);
                }elseif ($id === '(新)' && $name !== '') {
                    // 新增
                    $db->insert([
                        'name' => $name,
                        'sort' => $sort,
                        'is_focus' => 0,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
            header("Location: " . BASE_URL . "/index.php");
            exit;
        }
    }



}
?>