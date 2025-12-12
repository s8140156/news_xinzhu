<!-- <pre><?php var_dump(array_keys($permissions)); ?></pre> -->

<div class="card shadow">
    <div class="card-header">
        <h6 class="fw-bold mb-0">權限設定</h6>
    </div>
    <div class="card-body">

        <?php foreach ($modules as $module): ?>
            <?php $moduleId = $module['id']; ?>
            <div class="border rounded p-3 mb-3">

                <!-- 模組 -->
                <div class="form-check mb-2">
                    <input class="form-check-input module-toggle"
                        type="checkbox"
                        name="permissions[<?= $moduleId ?>][can_view]"
                        data-module="<?= $module['id'] ?>"
                        <?= ($permissions[$moduleId]['can_view'] ?? 0) ? 'checked' : '' ?>>
                    <label class="form-check-label fw-bold">
                        <?= $module['module_name'] ?> (可查看)
                    </label>
                </div>

                <!-- CRUD -->
                <div class="ms-4">
                    <?php foreach (['can_create' => '新增', 'can_edit' => '編輯', 'can_delete' => '刪除'] as $perm => $label): ?>
                        <label class="me-3">
                            <input type="checkbox"
                                name="permissions[<?= $moduleId ?>][<?= $perm ?>]"
                                class="crud crud-<?= $moduleId ?>"
                                <?= ($permissions[$moduleId][$perm] ?? 0) ? 'checked' : '' ?>>
                            <?= $label ?>
                        </label>
                    <?php endforeach; ?>
                    <label class="me-3">
                        <input type="checkbox"
                            class="crud-all"
                            data-module="<?= $moduleId ?>">
                        全選
                    </label>
                </div>

            </div>
        <?php endforeach; ?>

    </div>
</div>

<script>
    // 模組勾選 → 控制 CRUD
    document.querySelectorAll('.module-toggle').forEach(el => {
        el.addEventListener('change', function() {
            const moduleId = this.dataset.module;
            document.querySelectorAll('.crud-' + moduleId).forEach(c =>{
                c.checked = false;
            });
        });
    });

    // CRUD 全選
    document.querySelectorAll('.crud-all').forEach(el => {
        el.addEventListener('change', function() {
            const moduleId = this.dataset.module;
            document.querySelectorAll('.crud-' + moduleId)
                .forEach(cb => cb.checked = this.checked);
        });
    });
</script>