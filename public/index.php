<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

define('APP_PATH', realpath(__DIR__ . '/../app'));
require_once APP_PATH . '/controllers/backend/dashboardController.php';

$controller = new DashboardController();
$controller->index();








?>