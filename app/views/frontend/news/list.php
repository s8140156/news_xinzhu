<div class="container my-4">

    <!-- 分類標題 -->
    <h3 class="fw-bold mb-4 text-secondary">
        <?= htmlspecialchars($currentCategory) ?>
    </h3>

    <!-- 若沒有文章 -->
    <?php if (empty($articles)): ?>
        <div class="alert alert-info">
            目前此分類沒有任何已發布文章。
        </div>
    <?php else: ?>

        <!-- 文章列表(卡片) -->
        <!-- <div class="row g-4">
            <?php foreach ($articles as $article): ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <a href="<?= BASE_URL ?>/?page=news_show&id=<?= $article['id'] ?>"
                        class="text-decoration-none text-dark">

                        <div class="card border-0 shadow-sm h-100"> -->

                            <!-- 封面 -->
                            <!-- <img src="<?= getCoverImage($article) ?>"
                                class="card-img-top"
                                alt="<?= htmlspecialchars($article['title']) ?>"
                                style="height:180px;object-fit:cover;border-radius:6px;">

                            <div class="card-body">

                                <h6 class="card-title fw-bold text-primary">
                                    <?= htmlspecialchars($article['title']) ?>
                                </h6>

                                <p class="text-muted small mb-2">
                                    <?= date('Y-m-d', strtotime($article['publish_time'])) ?>
                                </p> -->

                                <!-- 摘要（如無可省略） -->
                                <!-- <?php if (!empty($article['summary'])): ?>
                                    <p class="text-muted small">
                                        <?= htmlspecialchars($article['summary']) ?>
                                    </p>
                                <?php endif; ?>

                                <button class="btn btn-sm btn-primary mt-2">閱讀更多</button>

                            </div>
                        </div>

                    </a>
                </div>
            <?php endforeach; ?>
        </div> -->
        <!-- 文章列表(卡片)end -->

        <!-- 純文字文章列表 option -->
        <ul class="list-group list-group-flush shadow-sm">
            <?php foreach ($articles as $article): ?>
                <li class="list-group-item py-3 px-0 article-item">
                    <a href="<?= BASE_URL ?>/?page=news_show&id=<?= $article['id'] ?>"
                       class="d-flex justify-content-between align-items-center text-decoration-none text-dark">

                        <!-- 左：標題（會自動縮略成單行） -->
                        <span class="flex-grow-1 text-truncate me-3 d-flex align-items-center">
                            <!-- <i class="fas fa-caret-right me-2 text-muted"></i> -->
                            <i class="fas fa-angle-right"></i>&nbsp
                            <!-- <i class="fas fa-chevron-right"></i> -->
                            <?= htmlspecialchars($article['title']) ?>
                        </span>

                        <!-- 右：日期（固定寬度，永遠靠右） -->
                        <span class="text-muted small d-flex align-items-center" style="min-width: 110px; text-align: right;">
                            <!-- <i class="fa-solid fa-square-caret-right"></i> -->
                            <i class="far fa-clock me-1"></i>
                            <?= date('Y/m/d H:i', strtotime($article['publish_time'])) ?>
                        </span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <!-- 純文字文章列表end -->

    <?php endif; ?>

</div>

<style>
    
.list-group-item {
    background-color: transparent !important;
    border: none !important;
}

.list-group-item + .list-group-item {
    border-top: 1px solid #ddd !important;
}

.list-group, .list-group-item {
    background: transparent !important;
    border: none !important;
    box-shadow: none !important;
}

/* 列表每行 hover 動畫 */
.news-row {
    transition: background-color 0.15s ease, transform 0.15s ease;
}

.news-row:hover {
    background-color: #f7f7f7;
    transform: translateX(2px);
}

/* 連結文字 hover 顏色 */
.news-link:hover {
    color: #0d6efd !important;
}

/* 標題前 icon 顏色淡一點 */
.news-title-icon {
    color: #bbb;
    transition: color 0.2s ease;
}

/* 滑過時 icon 也變色 */
.news-row:hover .news-title-icon {
    color: #0d6efd;
}

/* 日期 icon 顏色 */
.news-row .fa-clock {
    color: #aaa;
}

/* 保留你的下底線設定 */
/* .list-group-item + .list-group-item {
    border-top: 1px solid #ddd !important;
} */

.article-item a {
    transition: background-color .2s ease, padding-left .2s ease;
}

.article-item:hover i {
    transform: translateX(2px);
    transition: 0.2s ease;
}



</style>