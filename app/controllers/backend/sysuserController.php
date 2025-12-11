<?php

require_once APP_PATH . '/core/db.php';
require_once APP_PATH . '/core/helpers.php';

class SysuserController {

    public function index() {
        $db = new DB('sysusers');

        $sysusers = $db->query("SELECT * FROM sysusers WHERE is_super_admin = ? ORDER BY id ASC", [0]);

        $content = APP_PATH . '/views/backend/sysuser/index.php';
        include APP_PATH . '/views/backend/layouts/main.php';
    }

    public function create() {

        
        $page_title = '新增管理者';
        
        // 判斷新增/編輯
        $is_edit = false;
        
        $error_message = $_SESSION['error_message'] ?? '';
        unset($_SESSION['error_message']);

        // 新增mode資料預設值
        $id = '';
        $name = '';
        $email = '';
        $phone = '';
        $status = 1;
        // 產生一組預設密碼給SA紀錄
        $default_password = bin2hex(random_bytes(4)); // 產生8位

        $content = APP_PATH . '/views/backend/sysuser/form.php';
        include APP_PATH . '/views/backend/layouts/main.php';
    }

    public function store() {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        if(!$name || !$email || !$password) {
            $_SESSION['error_message'] = '請填寫完整資料';
            header("Location: ?page=sysuer_create");
            exit;
        }
        $db = new DB('sysusers');
        // 檢查 email 是否重複
        $exists = $db->query("SELECT * FROM sysusers WHERE email = ? LIMIT 1", [$email]);
        if($exists) {
            $_SESSION['error_message'] = '此Email已被使用';
            header("Location: ?page=sysuser_create");
            exit;
        }
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $db->insert([
            'name' => $name,
            'email' => $email,
            'password' => $hashed,
            'status' => 1,
            'is_super_admin' => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        echo "<script>alert('帳號新增成功！');window.location = '?page=sysuser_list';</script>";
        exit;
    }

    public function edit($id) {
        $page_title = '編輯管理者';

        $error_message = $_SESSION['error_message'] ?? '';
        unset($_SESSION['error_message']);

        // 判斷新增/編輯
        $is_edit = true;
        $id = $_GET['id'] ?? null;
        $db = new DB('sysusers');
        $user = $db->find($id);
        
        if(!$user || $user['is_super_admin'] == 1) {
            $_SESSION['error_message'] ='找不到資料';
            header("Location: ?page=sysuser_list");
            exit;
        }
        $email = $user['email'];
        $name = $user['name'];
        $phone = $user['phone'];
        $status = $user['status'];

        $default_password = '';

        $content = APP_PATH . '/views/backend/sysuser/form.php';
        include APP_PATH . '/views/backend/layouts/main.php';
    }

    public function update($id) {
        $id = $_POST['id'] ?? null;
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $status = trim($_POST['status'] ?? '');
        $password = trim($_POST['password'] ?? ''); 
        $db = new DB('sysusers');
        $user = $db->find($id);

        if(!$user) {
            $_SESSION['error_message'] ='找不到資料';
            header("Location: ?page=sysuser_list");
            exit;
        }
        $exists = $db->query("SELECT * FROM sysusers WHERE email = ? AND id != ? LIMIT 1", [$email,$id]);
        if($exists) {
            $_SESSION['error_message'] = '此Email已被使用';
            header("Location: ?page=sysuser_edit&id={$id}");
            exit;
        }
        $update = [
            'name' => $name,
            'phone' => $phone,
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        if($password !== '') {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $update['password'] = $hashed;
        }
        $db->update($id, $update);
        echo "<script>alert('管理員帳號更新成功！');window.location = '?page=sysuser_list';</script>";
        exit;   
    }




}