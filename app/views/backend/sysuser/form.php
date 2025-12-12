<div class="container-fluid">

    <form action="?page=sysuser_<?= $is_edit ? 'update' : 'store' ?>" method="post">
        <input type="hidden" name="id" value="<?= $id ?>">

        <div class="row">

            <!-- 左側：帳號資訊 -->
            <div class="col-lg-5 col-md-12 mb-4">
                <?php include __DIR__ . '/_form_user_info.php'; ?>
            </div>

            <!-- 右側：權限設定 -->
            <div class="col-lg-7 col-md-12 mb-4">
                <?php include __DIR__ . '/_form_permissions.php'; ?>
            </div>

        </div>

        <!-- 底部操作 -->
        <!-- <div class="d-flex justify-content-end mt-3">
            <a href="?page=sysuser" class="btn btn-secondary me-2">取消</a>
            <button type="submit" class="btn btn-primary">
                <?= $is_edit ? '儲存修改' : '新增管理者' ?>
            </button>
        </div> -->
        <div class="text-end mt-4">
            <a href="?page=sysuser" class="btn btn-secondary">取消</a>
            <button type="submit" class="btn btn-primary"><?= $is_edit ? '儲存修改' : '新增帳號' ?></button>
        </div>
    </form>
</div>