<!DOCTYPE html>
<html lang="zh-Hant">

<head>
  <!-- 動態注入 OG Meta -->
  <?= $ogTags ?? '' ?>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($title) ? htmlspecialchars($title) : '馨築生活' ?></title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= STATIC_URL ?>/assets/backend/vendor/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="<?= STATIC_URL ?>/assets/frontend/css/style.css">


</head>

<body class="bg-light">

  <!-- 🔹 導覽列 -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top main-navbar">
    <!-- <div class="container"> -->
    <div class="container d-flex align-items-center">
      <a class="navbar-brand fw-bold text-primary" href="<?= BASE_URL ?>/index.php">馨築生活</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- <div class="collapse navbar-collapse" id="navMenu"> -->
      <div class="nav-categories-wrapper flex-grow-1 mx-3">
        <!-- <ul class="navbar-nav me-auto mb-2 mb-lg-0"> -->
        <ul class="nav nav-pills flex-nowrap">
          <?php foreach ($categories as $id => $name): ?>
            <li class="nav-item">
              <a class="nav-link" href="<?= BASE_URL ?>/?page=news_list&category=<?= $id ?>">
                <?= htmlspecialchars($name) ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <form class="d-flex nav-search" role="search" action="" method="get">
        <input type="hidden" name="page" value="search">
        <input class="form-control form-control-sm me-2" name="keyword" type="search" placeholder="搜尋新聞..." required>
        <button class="btn btn-outline-primary btn-sm" type="submit">搜尋</button>
      </form>
    </div>
  </nav>

  <!-- 主內容區：左側動態內容 + 右側固定側欄 -->
  <main class="container my-5">
    <div class="row">
      <!-- 左側主內容（各頁帶入的 $content） -->
      <div class="col-lg-9 col-md-8 col-12 mb-4">
        <?php include $content; ?>
      </div>

      <!-- 右側固定側欄 -->
      <aside class="col-lg-3 col-md-4 col-12">
        <!-- 廣告區 -->
        <div class="highlight-section mb-5" id="highlight-marquee" style="display:none">
          <!-- <h5 class="fw-bold text-secondary border-bottom pb-2">焦點即時</h5> -->
          <h5 class="fw-bold text-secondary border-bottom pb-2"><?= $siteTitles['home_right_top_title'] ?? '' ?></h5>
          <div class="marquee bg-light p-3 rounded small text-muted">
            <ul class="marquee-inner marquee-list" id="highlight-marquee-inner">

            </ul>
          </div>
        </div>

        <!-- 焦點新聞 -->
        <?php if (!empty($focusArticle)): ?>
          <div class="focus-section mb-5">
            <h5 class="fw-bold text-secondary border-bottom pb-2"><?= $focusCategory['name'] ?? '' ?></h5>
            <!-- <h5 class="fw-bold text-secondary border-bottom pb-2"><?= $siteTitles['home_right_middle_title'] ?? '' ?></h5> -->
            <div class="card border-0 shadow-sm">
              <img src="<?= getCoverImage($focusArticle) ?>" class="card-img-top" alt="<?= htmlspecialchars($focusArticle['title']) ?>">
              <div class="card-body">
                <!-- <h6 class="card-title text-dark"><?= htmlspecialchars($focusArticle['title']) ?></h6>
                <p class="text-muted small mb-2"><?= date('Y-m-d', strtotime($focusArticle['publish_time'])) ?></p> -->
                <a href="<?= BASE_URL ?>/?page=news_list&category=<?= $focusArticle['category_id'] ?>" class="btn btn-sm btn-primary">閱讀更多</a>
              </div>
            </div>
          </div>
        <?php endif; ?>

        <!-- 合作媒體 -->
        <?php if (!empty($partners)): ?>
          <div class="partner-section">
            <h5 class="fw-bold text-secondary border-bottom pb-2">合作媒體</h5>

            <div class="bg-light p-3 rounded d-flex flex-wrap gap-3 justify-content-center partner-list">
              <?php foreach ($partners as $p): ?>
                <a href="<?= BASE_URL ?>/?page=api_partner_click&id=<?= $p['id'] ?>"
                  target="_blank"
                  class="partner-logo d-block">
                  <img src="<?= STATIC_URL . '/' . $p['image'] ?>"
                    alt="合作媒體">
                </a>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>
      </aside>
    </div>
  </main>
  <!-- 🟥 頁尾標籤區 -->
  <div class="container py-3">
    <div class="text-center small">
      <?php foreach ($footerTags as $i => $tag): ?>
        <a href="?page=news_footer_show&id=<?= $tag['id'] ?>" class="text-secondary text-decoration-none me-2">
          <?= $tag['title'] ?>
        </a>
        <?php if ($i < count($footerTags) - 1): ?>
          |&nbsp;&nbsp;
        <?php endif; ?>
      <?php endforeach; ?>
    </div>

  </div>
  <!-- 頁尾 -->
  <footer class="text-center text-secondary py-3 small border-top bg-white">
    <p class="mb-1">
      總社信箱：
      <a href="mailto:hclife.news@gmail.com">hclife.news@gmail.com</a>
    </p>
    <p class="mb-1">馨築生活資訊媒體事業</p>
    <p class="mb-0">版權所有 未經同意請勿轉載</p>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<script>
  fetch('<?= BASE_URL ?>/?page=api_sponsorpicks_active')
    .then(res => res.json())
    .then(res => {
      if (!res.success || !res.data.length) return;

      const wrap = document.getElementById('highlight-marquee');
      const inner = document.getElementById('highlight-marquee-inner');

      // 清空
      inner.innerHTML = '';

      // 第一份清單render
      res.data.forEach((item, idx) => {
        const li = document.createElement('li');
        li.className = idx === 0 ? ' is-first' : '';
        li.innerHTML = `
    <a href="<?= BASE_URL ?>/?page=api_sponsorpicks_click&id=${item.id}">
      ${item.title}
    </a>
  `;
        inner.appendChild(li);
      });

      // 第二份clone清單
      res.data.forEach(item => {
        const li = document.createElement('li');
        li.innerHTML = `
      <a href="<?= BASE_URL ?>/?page=api_sponsorpicks_click&id=${item.id}">
        ${item.title}
      </a>
    `;
        inner.appendChild(li);
      });

      wrap.style.display = 'block';

      // 無縫滾動
      let y = 0;
      const speed = 0.3; // 👉 調整速度（數字越大越快）
      const singleHeight = inner.scrollHeight / 2;
      let paused = false; //控制暫停

      // hover 控制
      const marquee = document.querySelector('.marquee');
      marquee.addEventListener('mouseenter', () => {
        paused = true;
      });
      marquee.addEventListener('mouseleave', () => {
        paused = false;
      });

      function tick() {
        if (!paused) {
          y -= speed;

          if (Math.abs(y) >= singleHeight) {
            y = 0; // 無縫 reset
          }

          inner.style.transform = `translateY(${y}px)`;

          // ===== 動態計算目前第一筆 =====
          const items = inner.querySelectorAll('li');
          const itemHeight = items[0].offsetHeight;

          // 目前滾到第幾筆
          const index = Math.floor(Math.abs(y) / itemHeight);

          items.forEach(li => li.classList.remove('is-first'));

          // 因為有 clone，所以用 % 保護
          if (items[index]) {
            items[index].classList.add('is-first');
          }
        }

        requestAnimationFrame(tick);
      }

      tick();
    });
</script>