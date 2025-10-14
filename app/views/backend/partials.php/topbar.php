<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>
    <i class="fas fa-fw fa-address-book"></i>
    <h1 class="h5 mb-0 text-gray-800"><?= $page_title ?></h1>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">
        <!-- Nav Item - User Information -->

        <?php
        // include_once('./notification.php');
        ?>
        <!-- <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="notify" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">通知</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="notify">
                <div class="dropdown-item" data-toggle="modal">
                    全部已讀
                </div>
                <div class="dropdown-divider"></div>
                <div class="dropdown-item" data-toggle="modal">
                    通知1
                </div>
                <div class="dropdown-item" data-toggle="modal">
                    通知2
                </div>
            </div>
        </li> -->

        <div class="topbar-divider d-none d-sm-block"></div>

        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION['anti_username']; ?></span>
                <i class="fa fa-user"></i>
                <!-- <img class="img-profile rounded-circle" src="../images/logo.png"> -->
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <!--<a class="dropdown-item" href="#">
        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
        Profile
        </a>-->
                <div class="dropdown-divider"></div>
                <a href="./changeUserInfo.php" class="dropdown-item">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    個人資料
                </a>
                <a href="./changePassword.php" class="dropdown-item">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    修改密碼
                </a>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    登出系統
                </a>
            </div>
        </li>
    </ul>
</nav>

<!-- End of Topbar -->