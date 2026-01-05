<?php
// =============================
// 手機版首頁：最新分類新聞
// 規則：
// - 排除焦點分類 (id = 1)
// - 最多顯示 3 筆
// =============================

$maxShow = 3;
$count = 0;
?>
<section>
  <h5 class="fw-bold text-secondary border-bottom pb-2">
    最新分類新聞
  </h5>

  <?php foreach ($categoryList as $cat): ?>
    <?php
    // 排除焦點分類
    if ($cat['id'] == 1) continue;

    // 限制顯示筆數
    if ($count >= $maxShow) break;

    $count++;
    ?>

    <div class="card mb-4 border-0 shadow-sm">
      <img src="<?= $cat['cover_image'] ?>" class="card-img-top">

      <div class="card-body d-flex justify-content-between align-items-center">
        <h6 class="fw-bold text-primary mb-0">
          <?= htmlspecialchars($cat['name']) ?>
        </h6>

        <a href="?page=news_list&category=<?= $cat['id'] ?>"
          class="btn btn-outline-primary btn-sm">
          更多新聞
        </a>
      </div>
    </div>
  <?php endforeach; ?>
  <!-- 3️⃣ 假的「載入更多」 -->
  <div class="text-center my-4">
    <button class="btn btn-outline-secondary btn-sm" disabled>
      載入更多分類
    </button>
  </div>

</section>