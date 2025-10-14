<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center bg-white border sidebar-brand-text" href="project.php">
        <!-- <a class="sidebar-brand d-flex align-items-center justify-content-center bg-white border" href="member.php"> -->
        <!--<div class="sidebar-brand-icon rotate-n-15">
        <i class="fas fa-laugh-wink"></i>
    </div> -->
        <span class="mx-2" style="color:#4B7C8E;font-size:1rem;">
            Anti Basic<br>電子商城
            <!-- <a class="collapse-item" href="orderList.php" id="orderListphp" name="orderListphp">訂單列表</a> -->
        </span>
    </a>

    <hr class="sidebar-divider">

    <?php
    $permission_ids = [];
    $sql = " select * from sysuser_permissions 
    inner join permissions on sysuser_permissions.permission_id = permissions.id 
    where sysuser_permissions.user_id = '$anti_userid' ";
    // print_r($permission_ids);

    // echo $sql;
    if ($result = mysqli_query($link, $sql)) {
        while ($row = mysqli_fetch_array($result)) {
            $permission_ids[] = $row['id']; // 權限 ID
            // 或者你也可以把 module 和 action 做成陣列，視需求而定
        }
    }
    // print_r($permission_ids); // 用於除錯，顯示權限 ID
    ?>

    <?php if (in_array(1, $permission_ids)): ?>
        <li class="nav-item" id="parent_order" name="parent_order">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#parentOrder" aria-expanded="true" aria-controls="parentOrder">
                <i class="fas fa-fw fa-address-card"></i>
                <span>訂單管理</span>
            </a>
            <div id="parentOrder" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="orderInfo.php" id="orderInfophp" name="orderInfophp">訂單列表</a>
                </div>
            </div>
        </li>
    <?php endif; ?>

    <?php if (in_array(3, $permission_ids)): ?>
        <li class="nav-item" id="parent_goods" name="parent_goods">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#parentGoods" aria-expanded="true" aria-controls="parentGoods">
                <i class="fas fa-fw fa-address-card"></i>
                <span>商品管理</span>
            </a>
            <div id="parentGoods" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="goodsType.php" id="goodsTypephp" name="goodsTypephp">商品類別</a>
                    <a class="collapse-item" href="goods.php" id="goodsphp" name="goodsphp">商品列表</a>
                </div>
            </div>
        </li>
    <?php endif; ?>

    <?php if (in_array(7, $permission_ids)): ?>
        <li class="nav-item" id="parent_financial" name="parent_financial">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#parentFinancial" aria-expanded="true" aria-controls="parentFinancial">
                <i class="fas fa-fw fa-address-card"></i>
                <span>金物流管理</span>
            </a>
            <div id="parentFinancial" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <!-- <a class="collapse-item" href="financial.php" id="financialphp" name="financialphp">金流管理</a> -->
                    <a class="collapse-item" href="material.php" id="materialphp" name="materialphp">物流管理</a>
                </div>
            </div>
        </li>
    <?php endif; ?>

    <?php if (in_array(11, $permission_ids)): ?>
        <li class="nav-item" id="parent_banner" name="parent_banner">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#parentBanner" aria-expanded="true" aria-controls="parentBanner">
                <i class="fas fa-fw fa-address-card"></i>
                <span>首頁Banner設定</span>
            </a>
            <div id="parentBanner" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="banner.php" id="bannerphp" name="bannerphp">Banner</a>
                </div>
            </div>
        </li>
    <?php endif; ?>

    <?php if (in_array(15, $permission_ids)): ?>
        <li class="nav-item" id="parent_menu" name="parent_menu">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#parentMenu" aria-expanded="true" aria-controls="parentMenu">
                <i class="fas fa-fw fa-address-card"></i>
                <span>選單編輯</span>
            </a>
            <div id="parentMenu" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="menu.php" id="menuphp" name="menuphp">Menu</a>
                </div>
            </div>
        </li>
    <?php endif; ?>


    <?php if (in_array(19, $permission_ids)): ?>
        <li class="nav-item" id="parent_coupon" name="parent_coupon">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#parentCoupon" aria-expanded="true" aria-controls="parentCoupon">
                <i class="fas fa-fw fa-address-card"></i>
                <span>優惠代碼管理</span>
            </a>
            <div id="parentCoupon" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="coupon.php" id="couponphp" name="couponphp">優惠代碼設定</a>
                </div>
            </div>
        </li>
    <?php endif; ?>

    <?php if (in_array(23, $permission_ids)): ?>
        <li class="nav-item" id="parent_member" name="parent_member">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#parentMember" aria-expanded="true" aria-controls="parentMember">
                <i class="fas fa-fw fa-address-card"></i>
                <span>會員管理</span>
            </a>
            <div id="parentMember" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="member.php" id="memberphp" name="memberphp">會員列表</a>
                </div>
            </div>
        </li>
    <?php endif; ?>

    <?php if (in_array(26, $permission_ids)): ?>
        <li class="nav-item" id="parent_authority" name="parent_authority">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#parentAuthority" aria-expanded="true" aria-controls="parentAuthority">
                <i class="fas fa-fw fa-address-card"></i>
                <span>管理者權限管理</span>
            </a>
            <div id="parentAuthority" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="sysuser.php" id="sysuserphp" name="sysuserphp">帳號管理</a>
                </div>
            </div>
        </li>
    <?php endif; ?>

    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>