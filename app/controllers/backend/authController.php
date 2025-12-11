<?php

require_once APP_PATH . '/core/db.php';
require_once APP_PATH . '/core/helpers.php';

class AuthController {

    public function login() {
        // 顯示登入畫面
        $error_message = $_SESSION['login_error'] ?? '';
        unset($_SESSION['login_error']);
        include APP_PATH . '/views/auth/login.php';
    }

    public function doLogin() {
        // 接收資料
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if($email === '' || $password === '') {
            $_SESSION['login_error'] = '請輸入帳號與密碼';
            header("Location: ?page=login");
            exit;
        }
        // 查詢帳號
        $userDB = new DB('sysusers');
        $result = $userDB->query(
            $sql = "SELECT * FROM sysusers WHERE email = ? AND status = 1 LIMIT 1",
            [$email]);
        $user = $result[0] ?? null;

        // 確認帳號
        if(!$user) {
            $_SESSION['login_error'] = '帳號或密碼錯誤';
            header("Location: ?page=login");
            exit;
        }
        // 密碼比對
        if(!password_verify($password, $user['password'])) {
            $_SESSION['login_error'] = '帳號或密碼錯誤';
            header("Location: ?page=login");
            exit;
        }

        // 登入成功 設定session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] =$user['name'];
        $_SESSION['user_email'] =$user['email'];
        $_SESSION['is_super_admin'] =$user['is_super_admin'];

        header("Location: ?page=article_index");
        exit;
    }

    public function logout() {
        // 清除session
        session_destroy();
        header("Location: ?page=login");
        exit;
    }

}