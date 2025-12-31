<!-- <pre><?php var_dump(array_keys($permissions)); ?></pre> -->

<div class="card shadow">
    <div class="card-header">
        <h6 class="fw-bold mb-0">
            權限設定
            <i class="fas fa-info-circle"
                data-toggle="tooltip"
                title="
                <strong>權限說明</strong><br>
                可查看：可進入該功能模組頁面<br>
                新增：可建立新資料<br>
                編輯：可修改既有資料<br>
                刪除：可刪除資料<br>
                <br>
                ※ 未勾選可查看，其餘權限將不生效">
            </i>
        </h6>
    </div>

    <div class="card-body">

        <?php foreach ($modules as $module): ?>
            <?php $moduleId = $module['id']; ?>
            <?php
            if (in_array($module['module_key'], ['sysuser', 'permission'])) {
                continue;
            }
            ?>
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
                <?php if($moduleId == MODULE_ARTICLE): ?>
                <div class="ms-4">
                    <label class="me-3">
                        <input type="checkbox"
                            name="permissions[<?= $moduleId ?>][can_focus]"
                            <?= ($permissions[$moduleId]['can_focus'] ?? 0) ? 'checked' : '' ?>>
                            可操作焦點文章
                            <i class="fas fa-info-circle"
                                data-toggle="tooltip"
                                title="僅影響「焦點」分類的文章操作權限；未勾選時，仍可瀏覽焦點文章，但不可新增、編輯或刪除。">
                            </i>
                    </label>
                </div>
                <?php endif ?>

            </div>
        <?php endforeach; ?>

    </div>
</div>

<script>
    // 模組勾選 → 控制 CRUD
    document.querySelectorAll('.module-toggle').forEach(el => {
        el.addEventListener('change', function() {
            const moduleId = this.dataset.module;
            document.querySelectorAll('.crud-' + moduleId).forEach(c => {
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

    document.querySelectorAll('[data-bs-toggle="tooltip"]')
        .forEach(el => new bootstrap.Tooltip(el));
</script>
<style>
    .tooltip-inner {
        max-width: 280px;
        text-align: left;
        font-size: 0.85rem;
        line-height: 1.5;
        padding: 0.6rem 0.75rem;
    }
</style>