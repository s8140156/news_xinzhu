<?php
// =============================
// 手機版：搜尋畫面
// 規則：
// - 預設最多顯示 $maxShow 筆
// - 顯示假 Load more
// =============================

$maxShow = 10;
$count = 0;
?>

<section class="mobile-section article-search-section">

  <h3 class="section-title mb-3">
    搜尋結果：<?= htmlspecialchars($keyword) ?>
  </h3>
  <hr>

  <?php if (empty($results)): ?>
    <p>找不到相關文章。</p>
  <?php else: ?>

    <ul class="list-group list-group-flush shadow-sm">
      <?php foreach ($results as $article): ?>
        <?php
        // 限制顯示筆數
        if ($count >= $maxShow) break;
        $count++;
        ?>
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
              <?php if (!empty($article['publish_time'])): ?>
                <?= date('Y/m/d H:i', strtotime($article['publish_time'])) ?>
              <?php endif; ?>
            </span>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>

    <!-- 假的 Load more -->
    <?php if (count($results) > $maxShow): ?>
      <div class="text-center my-4">
        <button class="btn btn-outline-secondary btn-sm" disabled>
          載入更多文章
        </button>
      </div>
    <?php endif; ?>

  <?php endif; ?>

</section>