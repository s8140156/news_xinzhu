<div class="container my-2">

    <!-- 首頁標題 -->
    <h3 class="fw-bold mb-4 border-bottom d-inline-block pb-1 text-secondary">最新分類新聞</h3>
    <!-- <h3 class="fw-bold text-secondary mb-4 mt-lg-4 mt-md-3 mt-2">最新分類新聞</h3> -->


    <!-- 分類卡片區 -->
    <div class="row g-4">

        <?php foreach ($categoryList as $cat): ?>
            <?php if ($cat['id'] == 1) continue; ?>
            <div class="col-12 col-sm-6 col-lg-4">

                <a href="?page=news_list&category=<?= $cat['id'] ?>" class="text-decoration-none text-dark">
                    <div class="card border-0 shadow-sm h-100 category-card">

                        <!-- 圖片區 -->
                        <div class="position-relative">
                            <img src="<?= getCoverImage($cat['latest_article']) ?>"
                                class="card-img-top"
                                alt="<?= htmlspecialchars($cat['name']) ?>"
                                style="height: 180px; object-fit: cover; border-radius: 6px;">

                            <!-- hover 遮罩 -->
                            <div class="category-hover">
                                <span><?= htmlspecialchars($cat['name']) ?></span>
                            </div>
                        </div>

                        <div class="card-body d-flex justify-content-between align-items-center">
                            <h5 class="card-title text-primary fw-bold">
                                <?= htmlspecialchars($cat['name']) ?>
                            </h5>

                            <?php if ($cat['latest_article']): ?>
                                <!-- <p class="card-text text-muted small mb-1">
                                         <?= mb_substr(strip_tags($cat['latest_article']['title']), 0, 30) ?>
                                     </p>
                                     <p class="text-muted small">
                                         <?= date('Y-m-d', strtotime($cat['latest_article']['publish_time'])) ?>
                                     </p> -->
                                <a href="?page=news_list&category=<?= $cat['id'] ?>"
                                    class="btn btn-outline-primary btn-sm">
                                    更多新聞
                                </a>
                            <?php else: ?>
                                <p class="text-muted small mb-2">尚無文章</p>
                            <?php endif; ?>

                        </div>
                    </div>
                </a>

            </div>
        <?php endforeach; ?>

    </div>

</div>