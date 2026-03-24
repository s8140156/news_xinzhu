<?php

require_once APP_PATH . '/core/db.php';
require_once APP_PATH . '/core/helpers.php';
// 讀取設定檔（包含 UPLOAD_PATH / UPLOAD_URL）

class SiteSettingsController {

    public function index() {

        $db =new DB('site_settings');
        $rows = $db->all();
        $siteSettings = [];
        foreach($rows as $row) {
            if($row['is_backend_visible'] === 1) {
                $siteSettings[$row['setting_key']] = $row;
            }
        }
        // print_r($siteSettings);
        // exit;

        $content = APP_PATH . '/views/backend/site_settings/index.php';
        include APP_PATH . '/views/backend/layouts/main.php';
    }

    public function update() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = new DB('site_settings');
            foreach($_POST as $key => $value) {
                $row = $db->find(['setting_key' => $key]);
                if($row) {
                    $db->update($row['id'],[
                        'setting_value' => $value,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }
        }
        echo "<script>alert('標題設定成功！');window.location='?page=sitesettings_index';</script>";
        exit;
    }



        
}
