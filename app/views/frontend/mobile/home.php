<?php
// =============================
// 手機版首頁：最新分類新聞
// 規則：
// - 排除焦點分類 (id = 1)
// - 最多顯示 3 筆
// =============================

?>
<section>
  <h5 class="fw-bold text-secondary border-bottom pb-2">
    最新分類新聞
  </h5>

  <?php foreach ($categoryList as $cat): ?>
    <?php
    // 排除焦點分類
    if ($cat['id'] == 1) continue;
    ?>

    <div class="card mb-4 border-0 shadow-sm category-card d-none">
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
  <!-- 「載入更多」 -->
  <div class="text-center my-4">
    <button id="loadMoreCategoryBtn" class="btn btn-outline-primary btn-sm">
      載入更多分類
    </button>
  </div>

</section>

<script>
  const cards = document.querySelectorAll('.category-card');
  const loadMoreBtn = document.getElementById('loadMoreCategoryBtn');

  const STEP = 3;
  let currentIndex = 0;

  function showNextBatch() {
    const nextIndex = currentIndex + STEP;

    for (let i = currentIndex; i < nextIndex && i < cards.length; i++) {
      cards[i].classList.remove('d-none');
    }

    currentIndex = nextIndex;

    if (currentIndex >= cards.length) {
      loadMoreBtn.style.display = 'none';
    }
  }

  // 初始顯示第一批
  showNextBatch();

  loadMoreBtn.addEventListener('click', showNextBatch);
</script>