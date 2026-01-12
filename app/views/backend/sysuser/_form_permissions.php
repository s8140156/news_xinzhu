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
            <?php
            if (in_array($module['module_key'], ['sysuser', 'permission'])) {
                continue;
            }
            $moduleId = $module['id'];
            ?>

            <div class="border rounded p-3 mb-3">

                <!-- 模組 -->
                <div class="form-check d-flex align-items-center mb-2">
                    <input class="form-check-input module-toggle me-2"
                        type="checkbox"
                        name="permissions[<?= $moduleId ?>][can_view]"
                        data-module="<?= $moduleId ?>"
                        <?= ($permissions[$moduleId]['can_view'] ?? 0) ? 'checked' : '' ?>>

                    <label class="form-check-label fw-bold me-3">
                        <?= $module['module_name'] ?>（可查看）
                    </label>

                    <label class="mb-0">
                        <input type="checkbox"
                            class="crud-all"
                            data-module="<?= $moduleId ?>">
                        全選
                    </label>
                </div>

                <!-- CRUD + 子權限 -->
                <div class="crud-group is-disabled" data-module="<?= $moduleId ?>">
                    <div class="crud-inner pl-4">

                        <?php foreach (['can_create' => '新增', 'can_edit' => '編輯', 'can_delete' => '刪除'] as $perm => $label): ?>
                            <label class="me-3">
                                <input type="checkbox"
                                    name="permissions[<?= $moduleId ?>][<?= $perm ?>]"
                                    class="crud crud-<?= $moduleId ?>"
                                    <?= ($permissions[$moduleId][$perm] ?? 0) ? 'checked' : '' ?>>
                                <?= $label ?>
                            </label>
                        <?php endforeach; ?>

                        <?php if ($moduleId == MODULE_ARTICLE): ?>
                            <div class="mt-2">
                                <label>
                                    <input type="checkbox"
                                        name="permissions[<?= $moduleId ?>][can_focus]"
                                        <?= ($permissions[$moduleId]['can_focus'] ?? 0) ? 'checked' : '' ?>>
                                    可操作焦點文章
                                    <i class="fas fa-info-circle"
                                        data-toggle="tooltip"
                                        title="僅影響焦點分類文章的操作權限">
                                    </i>
                                </label>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>

            </div>

        <?php endforeach; ?>

    </div>
</div>

<script>
    // 啟用 Bootstrap 工具提示
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
        .forEach(el => new bootstrap.Tooltip(el));

    // 同步模組與 CRUD 權限狀態
    function syncModulePermission(moduleId) {
        const moduleToggle = document.querySelector(`.module-toggle[data-module="${moduleId}"]`);
        const crudGroup = document.querySelector(`.crud-group[data-module="${moduleId}"]`);
        const crudCheckboxes = crudGroup.querySelectorAll('input[type="checkbox"]');
        const crudAll = document.querySelector(`.crud-all[data-module="${moduleId}"]`);

        if (!moduleToggle.checked) {
            crudCheckboxes.forEach(cb => {
                cb.checked = false;
                cb.disabled = true;
            });
            if (crudAll) {
                crudAll.checked = false;
                crudAll.disabled = true;
            }
        } else {
            crudCheckboxes.forEach(cb => cb.disabled = false);
            if (crudAll) crudAll.disabled = false;
        }
    }

    // 初始化
    document.querySelectorAll('.module-toggle').forEach(toggle => {
        const moduleId = toggle.dataset.module;
        const crudGroup = document.querySelector(`.crud-group[data-module="${moduleId}"]`);

        function syncState() {
            const enabled = toggle.checked;

            // 你原本的邏輯
            syncModulePermission(moduleId);

            // UI / disabled
            crudGroup.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                cb.disabled = !enabled;
            });
            crudGroup.classList.toggle('is-disabled', !enabled);
        }

        // 初始
        syncState();

        // 切換
        toggle.addEventListener('change', syncState);
    });


    // 全選
    document.querySelectorAll('.crud-all').forEach(all => {
        all.addEventListener('change', () => {
            const moduleId = all.dataset.module;
            const crudCheckboxes = document.querySelectorAll(`.crud-${moduleId}`);

            crudCheckboxes.forEach(cb => {
                cb.checked = all.checked;
            });
        });
    });
</script>
<style>
    .tooltip-inner {
        max-width: 280px;
        text-align: left;
        font-size: 0.85rem;
        line-height: 1.5;
        padding: 0.6rem 0.75rem;
    }

    /* CRUD 被鎖定時的視覺狀態 */
    .crud-group.is-disabled {
        opacity: 0.55;
        pointer-events: none;
        /* 保險，避免點到 */
    }

    /* 讓 disabled checkbox 游標一致 */
    .crud-group.is-disabled input[type="checkbox"] {
        cursor: not-allowed;
    }

    /* 如果你想保留「可 hover 但不可點」的感覺 */
    .crud-group.is-disabled label {
        cursor: not-allowed;
    }
</style>