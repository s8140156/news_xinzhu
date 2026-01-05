<?php

require_once APP_PATH . '/core/helpers.php';
require_once APP_PATH . '/controllers/frontend/frontendController.php';

class FooterController extends FrontendController {

    public function show() {
        $id = $_GET['id'] ?? null;
        if(!$id) {
            echo "缺少頁尾標籤ID";
            return;
        }

        $db = new DB('footer_articles');
        $footer = $db->find($id);

        if(!$footer) {
            echo "找不到頁尾標籤文章";
            return;
        }
        $db->update($id, [
            'views' => ($footer['views'] ?? 0) + 1
        ]);

        $content = $footer['content'];
        $links = json_decode($footer['links'], true) ?? [];

        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $content);
        libxml_clear_errors();

        $anchors = $dom->getElementsByTagName('a');
        foreach ($anchors as $i => $a) {
            if (isset($links[$i])) {
                $footerId = $footer['id'];
                $a->setAttribute(
                    "onclick",
                    "recordLinkClick($footerId, $i)"
                );
                $a->setAttribute("target", "_blank");
            }
        }

        $body = $dom->getElementsByTagName('body')->item(0);
        $footer['content_html'] = $dom->saveHTML($body);

        if($this->isMobile) {
            // 渲染手機版首頁
            $this->renderMobile('frontend/mobile/footer.php', [
                'footer' => $footer,
                'links' => $links
            ]);
            return;
        }
        
        $this->render('frontend/footer/show.php', [
            'footer' => $footer,
            'links' => $links
        ]);

    }

    public function recordLinkClick() {
        $id = $_POST['id'] ?? null;
        $index = $_POST['index'] ?? null;

        if ($id === null || $id === '' || $index === null || $index === '') {
            echo "error";
            return;
        }
        $db = new DB('footer_articles');
        $footer = $db->find($id);

        // 確認 link_clicks 是否為 json
        $raw = $footer['link_clicks'] ?? '';
        if ($raw === null || $raw === '') {
            $clicks = [];
        } else {
            $clicks = json_decode($raw, true);
            if (!is_array($clicks)) {
                $clicks = [];
            }
        }

        // 計算點擊
        $clicks[$index] = ($clicks[$index] ?? 0) + 1;

        $db->update($id, [
            'link_clicks' => json_encode($clicks, JSON_UNESCAPED_UNICODE)
        ]);

        echo "ok";
    }






}