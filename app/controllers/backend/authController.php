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

        // 如果要先強制更改密碼 開放部分權限 只存基本身份資料
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] =$user['email'];

        if ($user['must_change_password'] == 1) {
            $_SESSION['force_change_password'] = true;
            header("Location: ?page=change_password");
            exit;
        }

        // 如果不需強制更改密碼 則存完整身份資料
        $_SESSION['user_name'] =$user['name'];
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

    public function changePassword() {
        if(empty($_SESSION['user_id'] || empty($_SESSION['force_change_password']))) {
            header("Location: ?page=login");
            exit;
        }
        $error_message = $_SESSION['change_password_error'] ?? '';
        unset($_SESSION['change_password_error']);

        include APP_PATH . '/views/auth/change_password.php';
    }

    public function doChangePassword() {
        if(empty($_SESSION['user_id'] || empty($_SESSION['force_change_password']))) {
            header("Location: ?page=login");
            exit;
        }
        $password = trim($_POST['password'] ?? '');
        $password2 = trim($_POST['password2'] ?? '');
        if($password === '' || $password2 === '') {
            $_SESSION['change_password_error'] = '請輸入新密碼';
            header("Location: ?page=change_password");
            exit;
        }
        if($password !== $password2) {
            $_SESSION['change_password_error'] = '兩次密碼輸入不一致，請重新輸入';
            header("Location: ?page=change_password");
            exit;
        }
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $db = new DB('sysusers');
        $db->update($_SESSION['user_id'], [
            'password' => $hashed,
            'must_change_password' => 0,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        unset($_SESSION['force_change_password']);

        $user = $db->find($_SESSION['user_id']);
        $_SESSION['user_name'] =$user['name'];
        $_SESSION['user_email'] =$user['email'];
        $_SESSION['is_super_admin'] =$user['is_super_admin'];   
        
        header("Location: ?page=article_index");
        exit;
    }


}