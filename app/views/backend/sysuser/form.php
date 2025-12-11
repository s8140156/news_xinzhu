<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><?= $page_title ?></h6>
    </div>
    <div class="card-body">

        <form method="POST" action="?page=sysuser_<?= $is_edit ? 'update' : 'store' ?>">

            <input type="hidden" name="id" value="<?= $id ?>">
            <!-- 帳號（Email） -->
            <div class="mb-3">
                <label class="form-label">帳號（Email） <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control"
                    value="<?= htmlspecialchars($email) ?>"
                    <?= $is_edit ? 'readonly' : '' ?> required>
            </div>
            <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger mt-3">
                <?= htmlspecialchars($error_message) ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>


            <!-- 顯示預設密碼（新增用） -->
            <?php if (!$is_edit): ?>
                <div class="mb-3">
                    <label class="form-label">預設密碼（請先記下）</label>
                    <div class="input-group">
                        <input type="text" name="password" class="form-control"
                            value="<?= htmlspecialchars($default_password) ?>" id="passwordInput">
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('passwordInput')">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                    <small class="text-muted">使用者首次登入後會被強制修改密碼。</small>
                </div>
            <?php else: ?>
                <!-- 修改密碼（可留空） -->
                <div class="mb-3">
                    <label class="form-label">新密碼（留空則不修改）</label>
                    <div class="input-group">
                        <input type="password" name="password" class="form-control" id="passwordInput">
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('passwordInput')">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                </div>
            <?php endif; ?>

            <!-- 名稱 -->
            <div class="mb-3">
                <label class="form-label">名稱 <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control"
                    value="<?= htmlspecialchars($name) ?>" required>
            </div>

            <!-- 電話 -->
            <div class="mb-3">
                <label class="form-label">電話</label>
                <input type="text" name="phone" class="form-control"
                    value="<?= htmlspecialchars($phone ?? '') ?>">
            </div>

            <!-- 狀態 -->
            <div class="mb-3">
                <label class="form-label">帳號狀態</label>
                <select class="form-control" name="status">
                    <option value="1" <?= $status == 1 ? 'selected' : '' ?>>啟用</option>
                    <option value="0" <?= $status == 0 ? 'selected' : '' ?>>停用</option>
                </select>
            </div>

            <div class="text-end mt-4">
                <a href="?page=sysuser" class="btn btn-secondary">取消</a>
                <button type="submit" class="btn btn-primary"><?= $is_edit ? '儲存修改' : '新增帳號' ?></button>
            </div>

        </form>
    </div>
</div>

<!-- JS：切換顯示密碼 -->
<script>
    function togglePassword(id) {
        const field = document.getElementById(id);
        field.type = (field.type === "password") ? "text" : "password";
    }
</script>