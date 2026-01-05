<?php
// =============================
// 手機版：分類文章列表
// 規則：
// - 預設最多顯示 $maxShow 筆
// - 顯示假 Load more
// =============================

$maxShow = 10;
$count = 0;
?>

<section class="mobile-section article-list-section">

  <h3 class="section-title mb-3">
    <?= htmlspecialchars($currentCategory) ?>
  </h3>

  <?php if (empty($articles)): ?>
    <div class="alert alert-info">
      目前此分類沒有任何已發布文章。
    </div>
  <?php else: ?>

    <ul class="list-group list-group-flush shadow-sm">

      <?php foreach ($articles as $article): ?>
        <?php
        // 限制顯示筆數
        if ($count >= $maxShow) break;
        $count++;
        ?>

        <li class="list-group-item py-3 px-0 article-item">
          <a href="<?= BASE_URL ?>/?page=news_show&id=<?= $article['id'] ?>"
            class="d-flex justify-content-between align-items-center text-decoration-none text-dark">

            <!-- 左：標題 -->
            <span class="flex-grow-1 text-truncate me-3 d-flex align-items-center">
              <i class="fas fa-angle-right me-2 text-muted"></i>
              <?= htmlspecialchars($article['title']) ?>
            </span>

            <!-- 右：日期 -->
            <span class="text-muted small d-flex align-items-center"
              style="min-width: 110px; text-align: right;">
              <i class="far fa-clock me-1"></i>
              <?= date('Y/m/d H:i', strtotime($article['publish_time'])) ?>
            </span>

          </a>
        </li>

      <?php endforeach; ?>

    </ul>

    <!-- 假的 Load more -->
    <?php if (count($articles) > $maxShow): ?>
      <div class="text-center my-4">
        <button class="btn btn-outline-secondary btn-sm" disabled>
          載入更多文章
        </button>
      </div>
    <?php endif; ?>

  <?php endif; ?>

</section>