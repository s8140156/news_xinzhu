<div class="container-fluid">
    <div class="card shadow mb-4">

        <!-- Header -->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">頁尾標籤管理</h6>

            <!-- 新增按鈕 -->
            <a href="?page=footer_create" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> 新增頁尾標籤
            </a>
        </div>

        <div class="card-body">

            <?php if (empty($footers)): ?>
                <p class="text-muted">目前尚未設定頁尾標籤文章</p>
            <?php endif; ?>

            <!-- 📌 頁尾標籤卡片區 -->
            <?php foreach ($footers as $footer): ?>
                <div class="footer-card border rounded mb-3 d-flex align-items-stretch" data-id="<?= $footer['id'] ?>">
                    <!-- 拖曳 icon -->
                    <div class="drag-handle" title="拖曳調整順序">
                        <i class="fas fa-grip-vertical"></i>
                    </div>

                    <div class="flex-grow-1 p-3">

                        <!-- <div class="d-flex justify-content-between align-items-start flex-wrap"> -->

                            <!-- 狀態 + 標題 -->
                            <div class="d-flex align-items-center flex-wrap mb-2">
                                <?php if ($footer['status'] === 'published'): ?>
                                    <span class="badge bg-success text-white me-2">已發布</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary text-white me-2">草稿</span>
                                <?php endif; ?>

                                <h5 class="fw-bold mb-0 text-truncate">
                                    <?= htmlspecialchars($footer['title']) ?>我是id:<?= $footer['id'] ?>
                                </h5>
                            </div>

                            <?php
                            // 解析 links
                            $links = [];
                            if (!empty($footer['links']) && is_string($footer['links'])) {
                                $decoded = json_decode($footer['links'], true);
                                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                    $links = $decoded;
                                }
                            }

                            // 解析 link_clicks
                            $linkClicks = [];
                            if (!empty($footer['link_clicks'])) {
                                $decoded = json_decode($footer['link_clicks'], true);
                                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                    $linkClicks = $decoded;
                                }
                            }
                            ?>

                            <!-- 統計資訊 -->
                            <div class="text-secondary small d-flex flex-wrap mb-2">
                                <span class="me-3">最後修改：<?= date('Y/m/d H:i', strtotime($footer['updated_at'])) ?> |&nbsp;</span>
                                <span class="me-3">瀏覽次數：<?= $footer['views'] ?> 次 |&nbsp;</span>
                                <span>連結數量：<?= count($links) ?></span>
                            </div>

                            <!-- 連結清單 -->
                            <?php if (!empty($links)): ?>
                                <div class="text-secondary small lh-sm">
                                    <?php foreach ($links as $idx => $link): ?>
                                        <div class="mb-1">
                                            連結 <?= $idx + 1 ?>：
                                            <span class="link-display">
                                                <?= htmlspecialchars($link['text'] ?: '') ?>
                                            </span>
                                            點擊次數：<?= $linkClicks[$idx] ?? 0 ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-secondary small lh-sm"><strong>(無附加連結)</strong></div>
                            <?php endif; ?>
                        <!-- </div> -->
                    </div>

                    <!-- 操作按鈕 -->
                    <div class="d-flex align-items-start mt-2 mt-md-0 ms-md-3">
                        <a href="index.php?page=footer_show&id=<?= $footer['id'] ?>"
                            class="btn btn-light btn-sm me-2"
                            title="預覽" target="_blank">
                            <i class="fas fa-eye"></i>
                        </a>

                        <a href="?page=footer_edit&id=<?= $footer['id'] ?>"
                            class="btn btn-light btn-sm me-2"
                            title="編輯">
                            <i class="fas fa-edit"></i>
                        </a>

                        <a href="?page=footer_delete&id=<?= $footer['id'] ?>"
                            class="btn btn-light btn-sm text-danger"
                            title="刪除"
                            onclick="return confirm('確定要刪除此頁尾標籤文章嗎？');">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </div>


                </div>
            <?php endforeach; ?>

        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const container = document.querySelector('.card-body');

        if (!container) return;

        Sortable.create(container, {
            animation: 150,
            handle: '.drag-handle',
            draggable: '.footer-card',
            ghostClass: 'sortable-chosen',

            onEnd: function() {
                const items = document.querySelectorAll('.footer-card');
                let order = [];

                items.forEach((item, index) => {
                    order.push({
                        id: item.dataset.id,
                        sort: index + 1
                    });
                });

                // 送到後端儲存
                fetch('?page=api_footer_sort', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(order)
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (!data.success) {
                            alert('順序更新失敗');
                        }
                    })
                    .catch(() => {
                        alert('排序更新發生錯誤');
                    });
            }
        });

    });
</script>
<style>
    /* 連結灰底框 */
    .link-display {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        padding: 2px 6px;
        display: inline-block;
        min-width: 150px;
        color: #333;
    }

    .btn-light {
        background: #f8f9fa;
        border: 1px solid #e0e0e0;
    }

    .btn-light:hover {
        background: #f1f1f1;
    }

    .badge {
        font-size: 0.85rem;
        padding: 0.4em 0.4em;
        border-radius: 6px;
        margin-right: 8px;
    }

    .drag-handle {
        cursor: grab;
        color: #adb5bd;
    }

    .drag-handle:hover {
        color: #495057;
    }

    .footer-item.sortable-chosen {
        background-color: #f8f9fa;
    }

    .drag-handle {
        width: 36px;
        min-width: 36px;
        /* background-color: #f8f9fa; */
        /* border-right: 1px solid #e5e7eb; */

        display: flex;
        align-items: center;
        /* 垂直置中 */
        justify-content: center;
        /* 水平置中 */

        cursor: grab;
    }

    .drag-handle i {
        color: #9ca3af;
        /* 淡灰 */
        font-size: 18px;
    }

    .footer-card:hover .drag-handle {
        background-color: #eef2ff;
    }

    .footer-card:hover .drag-handle i {
        color: #4f46e5;
        /* hover 時藍色 */
    }

    .drag-handle:active {
        cursor: grabbing;
    }

    .sortable-ghost {
        opacity: 0.4;
    }
</style>