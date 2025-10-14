<!-- <h1>這是topbar</h1> -->

<!-- 🧱 Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- 📱 Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- 🏷️ 頁面標題（暫時固定文字，不帶變數） -->
    <i class="fas fa-fw fa-address-book"></i>
    <h1 class="h5 mb-0 text-gray-800">後台管理系統</h1>

    <!-- 🔔 Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- 📢 通知區塊 (目前靜態展示) -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter -->
                <span class="badge badge-danger badge-counter">3+</span>
            </a>
            <!-- Dropdown -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">通知中心</h6>
                <a class="dropdown-item" href="#">這是通知範例 1</a>
                <a class="dropdown-item" href="#">這是通知範例 2</a>
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- 👤 使用者區塊（目前靜態） -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">管理員</span>
                <i class="fa fa-user"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    個人資料
                </a>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-key fa-sm fa-fw mr-2 text-gray-400"></i>
                    修改密碼
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    登出系統
                </a>
            </div>
        </li>
    </ul>
</nav>
<!-- 🔚 End of Topbar -->