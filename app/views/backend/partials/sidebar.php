<?php
// 功能模組選單及樣式設定
$sidebarConfig = [
    'article' => [
        'icon' => 'fa-newspaper',
        'children' => [
            ['label' => '新增文章', 'page' => 'article_create', 'perm' => 'create'],
            ['label' => '文章列表', 'page' => 'article_index', 'perm' => 'view'],
        ],
    ],
    'category' => [
        'icon' => 'fa-list-alt',
        'children' => [
            ['label' => '類別列表', 'page' => 'category_index'],
        ],
    ],
    'sponsored' => [
        'icon' => 'fa-images',
        'children' => [
            ['label' => '廣告列表', 'page' => 'sponsorpicks_index'],
        ],
    ],
    'partner' => [
        'icon' => 'fa-handshake',
        'children' => [
            ['label' => '媒體列表', 'page' => 'partner_index'],
        ],
    ],
    'footer' => [
        'icon' => 'fa-pen',
        'children' => [
            ['label' => '管理列表', 'page' => 'footer_index'],
        ],
    ],
    'sysuser' => [
        'icon' => 'fa-user-lock',
        'children' => [
            ['label' => '管理員帳號', 'page' => 'sysuser_list'],
        ],
    ],
];

$moduleDB = new DB('modules');
$modules = $moduleDB->all("1 ORDER BY sort_order ASC");
?>

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center bg-white border"
       href="<?= BASE_URL ?>/?page=article_index">
        <span class="mx-2" style="color:#4B7C8E;font-size:1rem;">
            XinZhu<br>新聞後台
        </span>
    </a>

    <hr class="sidebar-divider">

    <?php foreach ($modules as $m): ?>
        <?php
        $moduleId = (int)$m['id'];
        // === 權限判斷（只看 can_view）===
        if (!$_SESSION['is_super_admin']) {
            $perm = $_SESSION['permissions'][$m['id']] ?? null;
            if (!$perm || !$perm['can_view']) continue;
        }

        $key = $m['module_key'];
        if (!isset($sidebarConfig[$key])) continue;

        $config = $sidebarConfig[$key];
        $collapseId = 'menu_' . $key;
        ?>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#"
               data-toggle="collapse" data-target="#<?= $collapseId ?>">
                <i class="fas fa-fw <?= $config['icon'] ?>"></i>
                <span><?= htmlspecialchars($m['module_name']) ?></span>
            </a>
        
            <div id="<?= $collapseId ?>" class="collapse">
                <div class="bg-white py-2 collapse-inner rounded">
                    <?php foreach ($config['children'] as $child): ?>
                        <?php 
                        $allowed = true;
                        $permType = $child['perm'] ?? 'view'; // 預設是view
                        if (!$_SESSION['is_super_admin']) {
                            if ($permType === 'view' && !canView($moduleId)) {
                                $allowed = false;
                            }
                            if ($permType === 'create' && !canCreate($moduleId)) {
                                $allowed = false;
                            }
                        }
                        ?>
                        <?php if ($allowed && !empty($child['page'])): ?>
                        <a class="collapse-item"
                           href="<?= BASE_URL ?>/?page=<?= $child['page'] ?>">
                            <?= htmlspecialchars($child['label']) ?>
                        </a>
                        <?php endif; ?>

                    <?php endforeach; ?>
                </div>
            </div>
        </li>

    <?php endforeach; ?>

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
