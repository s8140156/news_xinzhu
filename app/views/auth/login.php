<!DOCTYPE html>
<html lang="zh-Hant">

<head>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
    <title>(登入)馨築生活管理後台</title>

    <!-- Bootstrap -->
    <!-- <link rel="stylesheet" href="<?= STATIC_URL ?>/assets/backend/vendor/bootstrap/css/bootstrap.min.css"> -->

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= STATIC_URL ?>/assets/backend/vendor/fontawesome-free/css/all.min.css">

    <!-- SB Admin -->
    <link rel="stylesheet" href="<?= STATIC_URL ?>/assets/backend/css/sb-admin-2.min.css">

    <!-- Custom -->
    <link rel="stylesheet" href="<?= STATIC_URL ?>/assets/backend/css/style.css">

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- <script src="<?= STATIC_URL ?>/assets/backend/vendor/jquery/jquery.min.js"></script> -->
    <!-- <script src="<?= STATIC_URL ?>/assets/backend/vendor/bootstrap/js/bootstrap.bundle.min.js"></script> -->

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
                    <div class="text-center small mt-3">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">
                            忘記密碼？
                        </a>
                    </div>

                </div>

            </div>

        </div>
    </div>
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="?page=forget_password" id="forgotPasswordForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">忘記密碼</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label>請輸入帳號 Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                        <button type="button" id="forgotSubmitBtn" class="btn btn-primary">送出</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


</body>

</html>
<script>
document.getElementById('forgotSubmitBtn').addEventListener('click', function () {
    const btn = this;
    const form = document.getElementById('forgotPasswordForm');
    const formData = new FormData(form);

    // 防止連點
    btn.disabled = true;
    btn.innerText = '送出中...';

    fetch('?page=forget_password', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);

        if (data.status) {
            const modalEl = document.getElementById('forgotPasswordModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();
        }
    })
    .catch(err => {
        console.error(err);
        alert('系統錯誤，請稍後再試');
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerText = '送出';
    });
});

</script>

