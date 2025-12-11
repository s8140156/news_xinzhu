<!DOCTYPE html>
<html lang="zh-Hant">

<head>
    <meta charset="UTF-8">
    <title>(登入)馨築生活管理後台</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="<?= STATIC_URL ?>/assets/backend/vendor/bootstrap/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= STATIC_URL ?>/assets/backend/vendor/fontawesome-free/css/all.min.css">

    <!-- SB Admin -->
    <link rel="stylesheet" href="<?= STATIC_URL ?>/assets/backend/css/sb-admin-2.min.css">

    <!-- Custom -->
    <link rel="stylesheet" href="<?= STATIC_URL ?>/assets/backend/css/style.css">
</head>

<body class="bg-gradient-primary">

    <div class="container mt-5">
        <div class="row justify-content-center">

            <div class="col-md-6">

                <div class="card shadow p-4">
                    <h4 class="text-center mb-4">馨築生活管理後台</h4>

                    <form action="?page=doLogin" method="POST">
                        <div class="form-group mb-3">
                            <label>帳號</label>
                            <input type="text" class="form-control" name="email" required>
                        </div>

                        <div class="form-group mb-4">
                            <label>密碼</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">登入系統</button>
                    </form>
                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger mt-3">
                            <?= htmlspecialchars($error_message) ?>
                        </div>
                    <?php endif; ?>

                </div>

            </div>

        </div>
    </div>

    <!-- JS -->
    <script src="<?= STATIC_URL ?>/assets/backend/vendor/jquery/jquery.min.js"></script>
    <script src="<?= STATIC_URL ?>/assets/backend/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>