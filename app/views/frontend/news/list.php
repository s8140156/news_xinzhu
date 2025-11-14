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

        <!-- 文章列表 -->
        <div class="row g-4">
            <?php foreach ($articles as $article): ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <a href="<?= BASE_URL ?>/?page=news_show&id=<?= $article['id'] ?>"
                        class="text-decoration-none text-dark">

                        <div class="card border-0 shadow-sm h-100">

                            <!-- 封面 -->
                            <img src="<?= getCoverImage($article) ?>"
                                class="card-img-top"
                                alt="<?= htmlspecialchars($article['title']) ?>"
                                style="height:180px;object-fit:cover;border-radius:6px;">

                            <div class="card-body">

                                <h6 class="card-title fw-bold text-primary">
                                    <?= htmlspecialchars($article['title']) ?>
                                </h6>

                                <p class="text-muted small mb-2">
                                    <?= date('Y-m-d', strtotime($article['publish_time'])) ?>
                                </p>

                                <!-- 摘要（如無可省略） -->
                                <?php if (!empty($article['summary'])): ?>
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
        </div>

    <?php endif; ?>

</div>