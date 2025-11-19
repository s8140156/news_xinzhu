<!-- 🧭 Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- 🌐 Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center bg-white border sidebar-brand-text"
        href="<?= BASE_URL ?>/?page=dashboard">
        <span class="mx-2" style="color:#4B7C8E;font-size:1rem;">
            XinZhu<br>新聞後台
        </span>
    </a>

    <hr class="sidebar-divider">


    <!-- 一、文章管理 -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#menuArticles">
            <i class="fas fa-fw fa-newspaper"></i>
            <span>文章管理</span>
        </a>
        <div id="menuArticles" class="collapse">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="<?= BASE_URL ?>/?page=article_create">➕ 新增文章</a>
                <a class="collapse-item" href="<?= BASE_URL ?>/?page=article_index">📄 文章列表</a>
            </div>
        </div>
    </li>


    <!-- 二、類別管理 -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#menuCategory">
            <i class="fas fa-fw fa-list-alt"></i>
            <span>類別管理</span>
        </a>
        <div id="menuCategory" class="collapse">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="<?= BASE_URL ?>/?page=category_index">📂 類別列表</a>
            </div>
        </div>
    </li>


    <!-- 三、廣告管理 功能還未開發先隱藏 -->
    <!-- <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#menuBanner">
            <i class="fas fa-fw fa-images"></i>
            <span>廣告管理</span>
        </a>
        <div id="menuBanner" class="collapse">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="<?= BASE_URL ?>/?page=banner_index">🏞️ Banner 管理</a>
            </div>
        </div>
    </li> -->


    <!-- 四、合作媒體 功能還未開發先隱藏 -->
    <!-- <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#menuMedia">
            <i class="fas fa-fw fa-handshake"></i>
            <span>合作媒體</span>
        </a>
        <div id="menuMedia" class="collapse">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="<?= BASE_URL ?>/?page=media_index">媒體列表 / 排序</a>
                <a class="collapse-item" href="<?= BASE_URL ?>/?page=media_create">新增合作媒體</a>
            </div>
        </div>
    </li> -->


    <!-- 五、頁尾標籤 功能還未開發先隱藏 -->
    <!-- <li class="nav-item">
        <a class="nav-link" href="<?= BASE_URL ?>/?page=footer_edit">
            <i class="fas fa-fw fa-pen"></i>
            <span>頁尾標籤</span>
        </a>
    </li> -->


    <!-- 六、使用者 / 權限管理 功能還未開發先隱藏 -->
    <!-- <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#menuAuth">
            <i class="fas fa-fw fa-user-lock"></i>
            <span>權限管理</span>
        </a>
        <div id="menuAuth" class="collapse">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="#">使用者列表（未開發）</a>
                <a class="collapse-item" href="#">角色管理（未開發）</a>
            </div>
        </div>
    </li> -->


    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
