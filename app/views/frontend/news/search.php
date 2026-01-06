<div class="container my-4">
    <h3>搜尋結果：<?= htmlspecialchars($keyword) ?></h3>
    <hr>

    <?php if (empty($results)): ?>
        <p>找不到相關文章。</p>
    <?php else: ?>
        <ul class="list-group list-group-flush shadow-sm">
            <?php foreach ($results as $article): ?>
                <li class="list-group-item py-3 px-0 article-item">
                    <a href="<?= BASE_URL ?>/?page=news_show&id=<?= $article['id'] ?>"
                        class="d-flex justify-content-between align-items-center text-decoration-none text-dark article-link">

                        <!-- 左：標題（會自動縮略成單行） -->
                        <span class="flex-grow-1 text-truncate me-3 d-flex align-items-center article-title-wrap">
                            <!-- <i class="fas fa-caret-right me-2 text-muted"></i> -->
                            <i class="fas fa-angle-right"></i>
                            <span class="article-title">
                                <?= htmlspecialchars($article['title']) ?>
                            </span>
                        </span>

                        <!-- 右：日期（固定寬度，永遠靠右） -->
                        <span class="text-muted small d-flex align-items-center article-date">
                            <!-- <i class="fa-solid fa-square-caret-right"></i> -->
                            <i class="far fa-clock me-1"></i>
                            <?php if(!empty($article['publish_time'])): ?>
                            <?= date('Y/m/d H:i', strtotime($article['publish_time'])) ?>
                            <?php endif; ?>
                        </span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <!-- <div class="row">
            <?php foreach ($results as $article): ?>
                <div class="col-md-4 mb-4">
                    <a href="?page=news_show&id=<?= $article['id'] ?>" class="text-decoration-none">

                        <div class="card h-100 shadow-sm">
                            <img 
                                src="<?= getCoverImage($article) ?>" 
                                class="card-img-top" 
                                alt="cover">

                            <div class="card-body">
                                <h5 class="card-title">
                                    <?= htmlspecialchars($article['title']) ?>
                                </h5>
                                <p class="card-text text-muted small">
                                    <?= date('Y-m-d', strtotime($article['publish_time'])) ?>
                                </p>
                            </div>
                        </div>

                    </a>
                </div>
            <?php endforeach; ?>
        </div> -->
    <?php endif; ?>
</div>