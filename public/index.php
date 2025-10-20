<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// 偵測當前專案根網址
$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http');
$baseUrl .= '://' . $_SERVER['HTTP_HOST'];
$baseUrl .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
// print_r($_SERVER);

define('BASE_URL', rtrim($baseUrl, '/'));
define('APP_PATH', realpath(__DIR__ . '/../app'));

// 載入controller
require_once APP_PATH . '/controllers/backend/dashboardController.php';
require_once APP_PATH . '/controllers/backend/newsController.php';

// 呼叫controller
// $controller = new DashboardController();
$controller = new NewsController();
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['act']) && $_POST['act'] === 'addCategory') {
    $controller->store();
} else {
    $controller->index();
}








?>