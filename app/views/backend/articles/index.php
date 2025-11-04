<!-- <form method="GET" action="">
    <input type="hidden" name="page" value="article_delete">
    <label for="id">輸入文章 ID：</label>
    <input type="number" name="id" id="id" placeholder="例如：87" required>
    <button type="submit" class="btn btn-danger btn-sm"
        onclick="return confirm('確定要刪除此文章嗎？此動作無法復原！')">
        刪除
    </button>
</form> -->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">文章管理</h6>
        </div>

        <div class="card-body">

            <!-- 🔍 搜尋區塊 -->
            <form method="GET" action="" class="mb-3">
                <div class="row align-items-end">
                    <!-- 類別 -->
                    <div class="col-md-3 mb-2">
                        <label for="category" class="form-label">類別：</label>
                        <select id="category" name="category" class="form-control">
                            <option value="">全部分類</option>
                            <option value="1">焦點新聞</option>
                            <option value="2">綜合新聞</option>
                            <option value="3">體育新聞</option>
                        </select>
                    </div>

                    <!-- 期間 -->
                    <div class="col-md-5 mb-2">
                        <label class="form-label">期間：</label>
                        <div class="d-flex align-items-center">
                            <input type="date" name="start_date" class="form-control me-2">
                            <span>~</span>
                            <input type="date" name="end_date" class="form-control ms-2">
                        </div>
                    </div>

                    <!-- 標題搜尋 -->
                    <div class="col-md-3 mb-2">
                        <label for="keyword" class="form-label">標題搜尋：</label>
                        <input type="text" id="keyword" name="keyword" class="form-control" placeholder="輸入關鍵字...">
                    </div>

                    <!-- 搜尋按鈕 -->
                    <div class="col-md-1 text-end mb-2">
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-search"></i> 搜尋
                        </button>
                    </div>
                </div>
            </form>

            <!-- 🔽 排序下拉選單 -->
            <div class="d-flex justify-content-start align-items-center mb-3">
                <form method="GET" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
                    <input type="hidden" name="page" value="article_index">
                    <div class="d-flex align-items-center">
                        <label for="sort_by" class="me-2 mb-0 text-muted">排序：</label>
                        <select class="form-control w-auto" name="sort_by" onchange="this.form.submit()">
                            <option value="latest" <?= $sort ==='latest' ? 'selected' : '' ?>>最新更新</option>
                            <option value="publish_desc" <?= $sort ==='publish_desc' ? 'selected' : '' ?>>已發布（時間新→舊）</option>
                            <option value="schedule_asc" <?= $sort ==='schedule_asc' ? 'selected' : '' ?>>排程（時間近→遠）</option>
                            <option value="draft_desc" <?= $sort ==='draft_desc' ? 'selected' : '' ?>>草稿（最近修改）</option>
                        </select>
                    </div>
                </form>
            </div>

            <!-- 📰 文章卡片區 -->
            <?php foreach($articles as $article): ?>
            <div class="article-card border rounded p-3 mb-3">
                <div class="d-flex justify-content-between align-items-start flex-wrap">
                    <div class="flex-grow-1">
                        <!-- 狀態 + 標題 -->
                        <div class="d-flex align-items-center flex-wrap mb-2">
                            <div class="me-2 d-flex flex-wrap align-items-center">
                                <?php if ($article['status'] === 'published'): ?>
                                <span class="badge bg-success text-white me-1">已發布</span>
                                <?php elseif ($article['status'] === 'scheduled'): ?>
                                <span class="badge bg-warning text-dark me-1">排程中</span>
                                <?php else: ?>
                                <span class="badge bg-secondary text-white me-1">草稿</span>
                                <?php endif; ?>
                                <span class="badge bg-danger text-white me-1 mx-2">
                                    <?= htmlspecialchars($categories[$article['category_id']] ?? '未分類') ?>
                                </span>
                            </div>
                            <h5 class="fw-bold mb-0 text-truncate" style="max-width: 100%;">
                                <?= htmlspecialchars($article['title']) ?>
                            </h5>
                        </div>

                        <!-- 時間與統計 -->
                        <?php
                        $links = [];
                        if(!empty($article['links']) && is_string($article['links'])) {
                            $decoded = json_decode($article['links'], true);
                            if(json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                $links = $decoded;
                            }
                        }
                        // print_r($links);
                        ?>
                        <div class="text-secondary small d-flex flex-wrap mb-2">
                            <?php if ($article['status'] === 'published'): ?>
                            <span class="me-3">上線時間：<?= date('Y/m/d H:i', strtotime($article['publish_time'])) ?>
                                |&nbsp&nbsp</span>
                            <?php elseif ($article['status'] === 'scheduled'): ?>
                            <span class="me-3">預計上線：<?= date('Y/m/d H:i', strtotime($article['publish_time'])) ?>
                                |&nbsp&nbsp</span>
                            <?php endif; ?>
                            <span class="me-3"> 最後修改：<?= date('Y/m/d H:i', strtotime($article['updated_at'])) ?>
                                |&nbsp&nbsp</span>
                            <span class="me-3">點擊數：<?= $article['views'] ?> 次 |&nbsp&nbsp</span>
                            <span>連結追蹤：<?= count($links) ?></span>
                        </div>

                        <!-- 連結清單 -->
                        <?php if(!empty($links)): ?>
                        <div class="text-secondary small lh-sm">
                            <?php foreach($links as $idx=>$link): ?>
                            <div class="mb-1">
                                連結 <?= $idx+1 ?>：<span
                                    class="link-display"><?= htmlspecialchars($link['text'] ? : '') ?></span>　點擊數：<?= rand(0,50) ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                        <div class="text-secondary small lh-sm">(此文章沒有附加連結)</div>
                        <?php endif; ?>
                    </div>

                    <!-- 功能按鈕區 -->
                    <div class="d-flex align-items-start mt-2 mt-md-0 ms-md-3">
                        <a href="#" class="btn btn-light btn-sm me-2" title="預覽">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="#" class="btn btn-light btn-sm me-2" title="編輯">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="#" class="btn btn-light btn-sm text-danger" title="刪除"
                            onclick="return confirm('確定要刪除此文章嗎？此動作無法復原！')">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>


            <!-- 延遲加載 -->
            <div class="text-center mt-4">
                <button class="btn btn-outline-secondary px-4">延遲加載</button>
            </div>

        </div>
    </div>
</div>

<style>
.article-card {
    transition: box-shadow 0.2s;
}

.article-card:hover {
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
}

.badge {
    font-size: 0.85rem;
    padding: 0.4em 0.7em;
    border-radius: 6px;
}

.btn-light {
    background: #f8f9fa;
    border: 1px solid #e0e0e0;
}

.btn-light:hover {
    background: #f1f1f1;
}

/* 連結灰底框 */
.link-display {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    padding: 2px 6px;
    display: inline-block;
    min-width: 120px;
    color: #333;
}

/* 美化下拉選單 */
.form-select {
    border: 1px solid #ced4da;
    background-color: #fff;
    border-radius: 0.375rem;
    padding: 0.375rem 2.25rem 0.375rem 0.75rem;
    appearance: none;
    background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23666' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14l-4.796-5.481A.5.5 0 013 5h10a.5.5 0 01.385.82l-4.796 5.48a.5.5 0 01-.77 0z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 12px 12px;
}
</style>