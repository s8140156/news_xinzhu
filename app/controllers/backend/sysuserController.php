<?php

require_once APP_PATH . '/core/db.php';
require_once APP_PATH . '/core/helpers.php';

class SysuserController {

    public function index() {
        
        $page_title = '管理者帳號列表';

        $db = new DB('sysusers');
        // $sysusers = $db->query("SELECT * FROM sysusers WHERE is_super_admin = ? ORDER BY id ASC", [0]);
        $users = $db->query("SELECT id, name, email, phone, status, created_at, updated_at FROM sysusers ORDER BY created_at DESC");

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
        $_SESSION['default_password'] = $default_password; // 暫存於session帶給store

        $moduleDB = new DB('modules');
        $modules = $moduleDB->all();

        $permissions = [];
        foreach($modules as $module) {
            $permissions[$module['id']] = [
                'can_view' => 0,
                'can_create' => 0,
                'can_edit' => 0,
                'can_delete' => 0,
            ];
        }
        $content = APP_PATH . '/views/backend/sysuser/form.php';
        include APP_PATH . '/views/backend/layouts/main.php';
    }

    public function store() {

        $name = trim($_POST['name'] ?? ''); // 帳號
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $status = trim($_POST['status'] ?? '');
        $permissions = $_POST['permissions'] ?? [];
        $plainPassword = $_SESSION['default_password'] ?? null;
        unset($_SESSION['default_password']);

        if(!$name || !$email) {
            $_SESSION['error_message'] = '請填寫完整資料';
            header("Location: ?page=sysuser_create");
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
        $hashed = password_hash($plainPassword, PASSWORD_DEFAULT);

        $userId = $db->insert([
            'name' => $name,
            'email' => $email,
            'password' => $hashed,
            'phone' => $phone,
            'status' => $status,
            'is_super_admin' => 0,
            'must_change_password' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // 處理權限資料
        // $moduleDB = new DB('modules');
        // $modules = $moduleDB->all();
        // $moduleMap = [];
        // foreach($modules as $module) {
        //     $moduleMap[$module['module_key']] = $module['id'];
        // }

        $permitDB = new DB('user_permissions');
        foreach($permissions as $moduleId => $perm) {
            $permitDB->insert([
                'user_id' => $userId,
                'module_id' => $moduleId,
                'can_view' => isset($perm['can_view']) ? 1 : 0,
                'can_create' => isset($perm['can_create']) ? 1 : 0,
                'can_edit' => isset($perm['can_edit']) ? 1 : 0,
                'can_delete' => isset($perm['can_delete']) ? 1 : 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
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

        $moduleDB = new DB('modules');
        $modules = $moduleDB->all();
        $permitDB = new DB('user_permissions');
        $rows = $permitDB->query("SELECT * FROM user_permissions WHERE user_id = ?", [$id]);
        $permissions = [];
        foreach($rows as $row) {
            $permissions[$row['module_id']] = [
                'can_view' => $row['can_view'],
                'can_create' => $row['can_create'],
                'can_edit' => $row['can_edit'],
                'can_delete' => $row['can_delete'],
            ];
        }

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
        $permissions = $_POST['permissions'] ?? []; 
        
        if(!$id || !$name) {
            $_SESSION['error_message'] ='找不到資料';
            header("Location: ?page=sysuser_edit&id={$id}");
            exit;
        }
        
        $db = new DB('sysusers');
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
        // $moduleDB = new DB('modules');
        // $modules = $moduleDB->all();
        // $moduleMap = [];
        // foreach($modules as $module) {
        //     $moduleMap[$module['module_key']] = $module['id'];
        // }
        $permitDB = new DB('user_permissions');
        // 刪除舊權限
        $permitDB->query("DELETE FROM user_permissions WHERE user_id = ?", [$id]);
        // 新增更新權限
        // echo '<pre>';
        // var_dump($_POST['permissions']);
        // exit;

        foreach($permissions as $moduleId => $perm) {
            // if(!isset($moduleMap[$moduleKey])) {
            //     continue;
            // }
            $permitDB->insert([
                'user_id' => $id,
                'module_id' => $moduleId,
                'can_view' => isset($perm['can_view']) ? 1 : 0,
                'can_create' => isset($perm['can_create']) ? 1 : 0,
                'can_edit' => isset($perm['can_edit']) ? 1 : 0,
                'can_delete' => isset($perm['can_delete']) ? 1 : 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }   
        echo "<script>alert('管理員帳號更新成功！');window.location = '?page=sysuser_list';</script>";
        exit;   
    }

    public function delete($id) {
        if(!$id) {
            abort403('缺少管理者ID');
        }
        if((int)$id === (int)$_SESSION['user_id']) {
            abort403('不能刪除自己的帳號');
        }
        $db = new DB('sysusers');
        $user = $db->find($id);
        if(!$user) {
            abort403('管理者不存在');
        }
        $db->delete($id);

        echo "<script>alert('帳號已刪除！');window.location = '?page=sysuser_list';</script>";
        exit;
    }




}