<?php

require_once APP_PATH . '/core/db.php';

$newsDB = new DB('news');
$news = $newsDB->all();







?>