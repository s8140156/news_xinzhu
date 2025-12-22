<?php

require_once APP_PATH . '/core/db.php';
require_once APP_PATH . '/core/helpers.php';

class ProfileController {

    public function index() {
        $error_message = $_SESSION['error_message'] ?? '';
        unset($_SESSION['error_message']);
        $success_message = $_SESSION['success_message'] ?? '';
        unset($_SESSION['success_message']);

        $tab = $_GET['tab'] ?? 'info';

        $db = new DB('sysusers');
        $user = $db->find($_SESSION['user_id']);

        $content = APP_PATH . '/views/backend/profile/index.php';
        include APP_PATH . '/views/backend/layouts/main.php';
    }

    public function updateInfo() {

        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');

        if(!$name) {
            $_SESSION['error_message'] = '名稱不可為空';
            header('Location: ?page=profile&tab=info');
            exit;
        }
        $db = new DB('sysusers');
        $db->update($_SESSION['user_id'], [
            'name' => $name,
            'phone' => $phone,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $_SESSION['success_message'] = '個人資料已更新';
        header('Location: ?page=profile&tab=info');
        exit;
    }

    public function updatePassword() {

        $password = $_POST['password'] ?? '';
        $confirm = $_POST['password_confirm'] ?? '';

        if(!$password || $password !== $confirm) {
            $_SESSION['error_message'] = '密碼不一致';
            header('Location: ?page=profile&tab=password');
            exit;
        }

        $db = new DB('sysusers');
        $db->update($_SESSION['user_id'], [
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'must_change_password' => 0,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        $_SESSION['success_message'] = '密碼已修改';
        header('Location: ?page=profile&tab=password');
        exit;

    }







}