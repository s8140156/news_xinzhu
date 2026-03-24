<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <form method="POST" action="<?= BASE_URL ?>/index.php?page=sitesettings_update" id="siteSettingsForm">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">首頁標題設定</h6>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead style="background-color: #f8f9fc;">
                                    <tr>
                                        <th class="align-middle" style="width: 25%;">前台顯示區塊</th>
                                        <th class="align-middle" style="width: 45%;">設定值</th>
                                        <!-- <th class="align-middle" style="width: 30%;">備註</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($siteSettings as $key => $setting): ?>
                                    <tr>
                                        <td class="align-middle"><?= htmlspecialchars($setting['description'] ?? '') ?></td>
                                        <td>
                                            <input
                                                type="text"
                                                name="<?= htmlspecialchars($key) ?>"
                                                class="form-control"
                                                value="<?= htmlspecialchars($setting['setting_value'] ?? '') ?>"
                                                placeholder="請輸入標題"
                                                <?= canEdit(MODULE_SITESETTINGS) ? '' : 'readonly' ?>
                                            >
                                        </td>
                                    </tr>
                                    
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if (canEdit(MODULE_SITESETTINGS)): ?>
                        <button type="submit" class="btn btn-info">儲存</button>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>