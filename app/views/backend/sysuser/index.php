<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><?= $page_title ?></h4>
    <a href="?page=sysuser_create" class="btn btn-primary">
        <i class="fa fa-plus"></i> 新增管理者
    </a>
</div>
<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>名稱</th>
                    <th>Email</th>
                    <th class="text-center">狀態</th>
                    <th class="text-center">建立時間</th>
                    <th class="text-center">操作</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            尚無管理者帳號
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td><?= htmlspecialchars($u['name']) ?></td>
                            <td><?= htmlspecialchars($u['email']) ?></td>

                            <!-- 狀態 -->
                            <td class="text-center">
                                <?php if ($u['status']): ?>
                                    <span class="badge bg-success">啟用</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">停用</span>
                                <?php endif; ?>
                            </td>

                            <td class="text-center small text-muted">
                                <?= date('Y-m-d', strtotime($u['created_at'])) ?>
                            </td>

                            <!-- 操作 -->
                            <td class="text-center">
                                <!-- 編輯 -->
                                <a href="?page=sysuser_edit&id=<?= $u['id'] ?>"
                                class="text-primary me-2"
                                title="編輯">
                                    <i class="fa fa-pen"></i>
                                </a>

                                <?php if (!empty($_SESSION['is_super_admin'])
                                        && empty($u['is_super_admin'])): ?>
                                    <!-- 刪除 -->
                                    <form method="POST"
                                        action="?page=sysuser_delete"
                                        style="display:inline"
                                        onsubmit="return confirm('確定要刪除此管理者帳號嗎？此動作無法復原');">

                                        <input type="hidden" name="id" value="<?= $u['id'] ?>">

                                        <button type="submit"
                                                class="btn btn-link p-0 text-danger"
                                                title="刪除">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>