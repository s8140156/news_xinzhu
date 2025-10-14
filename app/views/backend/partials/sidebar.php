<!-- <h1>這是sidebar</h1> -->

<!-- 🧭 Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- 🌐 Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center bg-white border sidebar-brand-text"
        href="#">
        <span class="mx-2" style="color:#4B7C8E;font-size:1rem;">
            XinZhu<br>新聞後台
        </span>
    </a>

    <hr class="sidebar-divider">

    <!-- 📂 模組：訂單管理 -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#parentOrder" aria-expanded="true"
            aria-controls="parentOrder">
            <i class="fas fa-fw fa-address-card"></i>
            <span>訂單管理</span>
        </a>
        <div id="parentOrder" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="#">訂單列表</a>
            </div>
        </div>
    </li>

    <!-- 📦 模組：商品管理 -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#parentGoods" aria-expanded="true"
            aria-controls="parentGoods">
            <i class="fas fa-fw fa-cubes"></i>
            <span>商品管理</span>
        </a>
        <div id="parentGoods" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="#">商品類別</a>
                <a class="collapse-item" href="#">商品列表</a>
            </div>
        </div>
    </li>

    <!-- 💰 模組：金物流管理 -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#parentFinancial"
            aria-expanded="true" aria-controls="parentFinancial">
            <i class="fas fa-fw fa-money-bill-wave"></i>
            <span>金物流管理</span>
        </a>
        <div id="parentFinancial" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="#">物流管理</a>
            </div>
        </div>
    </li>

    <!-- 🖼️ 模組：Banner設定 -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#parentBanner" aria-expanded="true"
            aria-controls="parentBanner">
            <i class="fas fa-fw fa-images"></i>
            <span>首頁 Banner 設定</span>
        </a>
        <div id="parentBanner" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="#">Banner 管理</a>
            </div>
        </div>
    </li>

    <!-- 👥 模組：會員管理 -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#parentMember" aria-expanded="true"
            aria-controls="parentMember">
            <i class="fas fa-fw fa-users"></i>
            <span>會員管理</span>
        </a>
        <div id="parentMember" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="#">會員列表</a>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
<!-- 🔚 End of Sidebar -->