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

    <ul class="list-group list-group-flush">
      <div id="articleList">
        <?php foreach ($articles as $article): ?>
          <?php
          // 限制顯示筆數
          if ($count >= $maxShow) break;
          $count++;
          ?>

          <li class="list-group-item py-3 px-0 article-item" data-id="<?= $article['id'] ?>" data-publish-time="<?= $article['publish_time'] ?>">
            <a href="<?= BASE_URL ?>/?page=news_show&id=<?= $article['id'] ?>"
              class="d-flex justify-content-between align-items-center text-decoration-none text-dark article-link">

              <!-- 左：標題 -->
              <span class="flex-grow-1 text-truncate me-3 d-flex align-items-center article-title-wrap">
                <i class="fas fa-angle-right me-2 article-title-icon"></i>
                <span class="article-title">
                  <?= htmlspecialchars($article['title']) ?>
                </span>
              </span>

              <!-- 右：日期 -->
              <span class="text-muted small d-flex align-items-center article-date">
                <i class="far fa-clock me-1"></i>
                <?= date('Y/m/d', strtotime($article['publish_time'])) ?>
              </span>

            </a>
          </li>

        <?php endforeach; ?>

      </div>


    </ul>

    <!-- 假的 Load more -->
    <?php if (count($articles) > $maxShow): ?>
      <div class="text-center my-4">
        <button type="button" id="loadMoreBtn" class="btn btn-outline-primary btn-sm load-more-btn">
          載入更多文章
        </button>
      </div>
    <?php endif; ?>

  <?php endif; ?>

</section>

<script>
  const categoryId = <?= (int)$categoryId ?>;
  const loadMoreBtn = document.getElementById('loadMoreBtn');
  const list = document.getElementById('articleList');

  function getLastCursor() {
    const items = document.querySelectorAll('.article-item');
    if (!items.length) return null;

    const last = items[items.length - 1];
    return {
      id: last.dataset.id,
      publish_time: last.dataset.publishTime,
    };
  }

  loadMoreBtn.addEventListener('click', () => {
    loadMoreBtn.disabled = true;
    const cursor = getLastCursor();
    // console.log('cursor', cursor);
    if (!cursor) {
      loadMoreBtn.disabled = false;
      return;
    }
    fetch(`?page=api_news_load_more&category_id=${categoryId}&last_id=${cursor.id}&last_publish_time=${encodeURIComponent(cursor.publish_time)}`)
      .then(res => res.json())
      .then(res => {
        if (!res.data || !res.data.length) {
          loadMoreBtn.style.display = 'none';
          return;
        }

        res.data.forEach(article => {
          const el = document.createElement('li');
          el.className = 'list-group-item py-3 px-0 article-item';
          el.dataset.id = article.id;
          el.dataset.publishTime = article.publish_time;
          el.innerHTML = `
          <a href="<?= BASE_URL ?>/?page=news_show&id=${article.id}"
              class="d-flex justify-content-between align-items-center text-decoration-none text-dark article-link">
  
              <!-- 左：標題 -->
              <span class="flex-grow-1 text-truncate me-3 d-flex align-items-center article-title-wrap">
                <i class="fas fa-angle-right me-2 article-title-icon"></i>
                <span class="article-title">
                  ${article.title}
                </span>
              </span>
  
              <!-- 右：日期 -->
              <span class="text-muted small d-flex align-items-center article-date">
                <i class="far fa-clock me-1"></i>
                ${article.publish_time.split(' ')[0].replace(/-/g, '/')}
              </span>
  
            </a>
        `;
          list.appendChild(el);
        });
        if (!res.has_more) {
          loadMoreBtn.style.display = 'none';
        }
      })
      .finally(() => {
        loadMoreBtn.disabled = false;
      });
  });
</script>

<style>

</style>