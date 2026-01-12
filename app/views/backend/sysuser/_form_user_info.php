<div class="card shadow">
    <div class="card-header">
        <h6 class="fw-bold mb-0"><?= $page_title ?></h6>
    </div>
    <div class="card-body">

        <!-- Email -->
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

        <!-- 預設密碼 / 修改密碼 -->
        <?php if (!$is_edit): ?>
            <div class="mb-3">
                <label class="form-label">預設密碼（請先記下）</label>
                <div class="input-group">
                    <!-- <input type="text" class="form-control"
                        value="<?= htmlspecialchars($default_password) ?>" readonly> -->
                    <input type="password" id="defaultPassword" class="form-control"
                        value="<?= htmlspecialchars($default_password) ?>" readonly>
                    <button class="btn btn-outline-secondary" type="button"
                        onclick="togglePassword(this)">
                        <i class="fa fa-eye"></i>
                    </button>
                </div>
                <small class="text-muted">使用者首次登入後需修改密碼</small>
            </div>
        <?php else: ?>
            <div class="mb-3">
                <label class="form-label">新密碼（留空不修改）</label>
                <input type="password" name="password" class="form-control">
            </div>
        <?php endif; ?>

        <!-- 名稱 -->
        <div class="mb-3">
            <label class="form-label">名稱 <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control"
                value="<?= htmlspecialchars($name) ?>" required>
        </div>
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger mt-3">
                <?= htmlspecialchars($error_message) ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <!-- 電話 -->
        <div class="mb-3">
            <label class="form-label">電話</label>
            <input type="text" name="phone" class="form-control"
                value="<?= htmlspecialchars($phone ?? '') ?>">
        </div>

        <!-- 狀態切換（選項 B） -->
        <div class="mb-3">
            <label class="form-label">帳號狀態</label><br>

            <button type="button"
                class="btn <?= $status ? 'btn-success' : 'btn-outline-secondary' ?>"
                id="statusToggle"
                onclick="toggleStatus()">
                <?= $status ? '啟用中' : '已停用' ?>
            </button>

            <input type="hidden" name="status" id="statusInput" value="<?= $status ?>">
        </div>

    </div>
</div>

<script>
    // 切換顯示密碼
    function togglePassword(btn) {
        const input = btn.closest('.input-group').querySelector('input');
        input.type = (input.type === "password") ? "text" : "password";
    }
    // 切換啟用/停用狀態
    function toggleStatus() {
        const input = document.getElementById('statusInput');
        const btn = document.getElementById('statusToggle');

        input.value = input.value == 1 ? 0 : 1;

        if (input.value == 1) {
            btn.className = 'btn btn-success';
            btn.innerText = '啟用中';
        } else {
            btn.className = 'btn btn-outline-secondary';
            btn.innerText = '已停用';
        }
    }
</script>