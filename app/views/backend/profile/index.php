<?php $tab = $tab ?? 'info'; ?>

<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link <?= $tab === 'info' ? 'active' : '' ?>"
            href="?page=profile&tab=info">個人資訊</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $tab === 'password' ? 'active' : '' ?>"
            href="?page=profile&tab=password">修改密碼</a>
    </li>
</ul>

<?php if ($tab === 'info'): ?>
<form method="post" action="?page=profile_update_info">
    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success mt-3">
            <?= htmlspecialchars($success_message) ?>
        </div>
    <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>
    <div class="mb-3">
        <label>Email（帳號）</label>
        <input type="text" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" disabled>
    </div>

    <div class="mb-3">
        <label>名稱 <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
    </div>
    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger mt-3">
            <?= htmlspecialchars($error_message) ?>
        </div>
    <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <div class="mb-3">
        <label>電話</label>
        <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
    </div>

    <button class="btn btn-primary">儲存</button>
</form>
<?php endif; ?>

<?php if ($tab === 'password'): ?>
<form method="post" action="?page=profile_update_password">
    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success mt-3">
            <?= htmlspecialchars($success_message) ?>
        </div>
    <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>
    <div class="mb-3">
        <label>新密碼</label>
        <input type="password" name="password" class="form-control">
    </div>

    <div class="mb-3">
        <label>確認新密碼</label>
        <input type="password" name="password_confirm" class="form-control">
    </div>
    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger mt-3">
            <?= htmlspecialchars($error_message) ?>
        </div>
    <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <button class="btn btn-danger">修改密碼</button>
</form>
<?php endif; ?>
