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

require_once APP_PATH . '/controllers/backend/dashboardController.php';

$controller = new DashboardController();
$controller->index();








?>