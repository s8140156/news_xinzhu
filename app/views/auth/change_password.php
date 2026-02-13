<!DOCTYPE html>
<html lang="zh-Hant">

<head>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
    <title>修改密碼</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="<?= STATIC_URL ?>/assets/backend/vendor/bootstrap/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= STATIC_URL ?>/assets/backend/vendor/fontawesome-free/css/all.min.css">

    <!-- SB Admin -->
    <link rel="stylesheet" href="<?= STATIC_URL ?>/assets/backend/css/sb-admin-2.min.css">

    <!-- Custom -->
    <link rel="stylesheet" href="<?= STATIC_URL ?>/assets/backend/css/style.css">
</head>

<body class="bg-light">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-xl-6 col-lg-8 col-md-9">

                <div class="card shadow-lg my-5">
                    <div class="card-body p-5">

                        <div class="text-center mb-4">
                            <h1 class="h4 text-gray-900">請修改密碼</h1>
                            <p class="text-muted small">首次登入或管理者重置後，必須先變更密碼才能進入系統。</p>
                        </div>

                        <?php if (!empty($error_message)): ?>
                            <div class="alert alert-danger">
                                <?= htmlspecialchars($error_message) ?>
                            </div>
                        <?php endif; ?>

                        <form action="?page=doChangePassword" method="POST">

                            <div class="form-group mb-3">
                                <label>新密碼：</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                            <div class="form-group mb-4">
                                <label>再次輸入新密碼：</label>
                                <input type="password" name="password2" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                變更密碼
                            </button>
                        </form>

                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- JS -->
    <script src="<?= STATIC_URL ?>/assets/backend/vendor/jquery/jquery.min.js"></script>
    <script src="<?= STATIC_URL ?>/assets/backend/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>