<!DOCTYPE html>
<html lang="zh-Hant">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($title) ? htmlspecialchars($title) : '馨築新聞網' ?></title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>/assets/css/frontend.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/frontend/css/style.css">
  <script src="<?= BASE_URL ?>/assets/frontend/js/main.js"></script>


</head>

<body class="bg-light">

  <!-- 🔹 導覽列 -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
      <a class="navbar-brand fw-bold text-primary" href="<?= BASE_URL ?>/index.php">馨築新聞網</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <?php foreach ($categories as $id => $name): ?>
            <li class="nav-item">
              <a class="nav-link" href="<?= BASE_URL ?>/?page=news_list&category=<?= $id ?>">
                <?= htmlspecialchars($name) ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>

        <form class="d-flex" role="search">
          <input class="form-control form-control-sm me-2" type="search" placeholder="搜尋新聞...">
          <button class="btn btn-outline-primary btn-sm">搜尋</button>
        </form>
      </div>
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
        <div class="ad-section mb-5">
          <h5 class="fw-bold text-secondary border-bottom pb-2">廣告區</h5>
          <div class="marquee bg-light p-3 rounded small text-muted">
            <marquee behavior="scroll" direction="up" scrollamount="2" height="140">
              <p>魔告標題跑馬燈 OOX</p>
              <p>這裡是廣告標題跑馬燈</p>
              <p>這裡是廣告標題跑馬燈</p>
              <p>這裡是廣告標題跑馬燈</p>
            </marquee>
          </div>
        </div>

        <!-- 焦點新聞 -->
        <?php if (!empty($focusArticle)): ?>
          <div class="focus-section mb-5">
            <h5 class="fw-bold text-secondary border-bottom pb-2">焦點新聞</h5>
            <div class="card border-0 shadow-sm">
              <img src="<?= getCoverImage($focusArticle) ?>" class="card-img-top" alt="<?= htmlspecialchars($focusArticle['title']) ?>">
              <div class="card-body">
                <!-- <h6 class="card-title text-dark"><?= htmlspecialchars($focusArticle['title']) ?></h6>
                <p class="text-muted small mb-2"><?= date('Y-m-d', strtotime($focusArticle['publish_time'])) ?></p> -->
                <a href="<?= BASE_URL ?>/?page=news_show&id=<?= $focusArticle['id'] ?>" class="btn btn-sm btn-primary">閱讀更多</a>
              </div>
            </div>
          </div>
        <?php endif; ?>

        <!-- 合作媒體 -->
        <div class="partner-section">
          <h5 class="fw-bold text-secondary border-bottom pb-2">合作媒體</h5>
          <div class="bg-light text-center p-3 rounded">
            <p class="text-muted small mb-2">此區預留合作媒體 LOGO 展示</p>
            <div class="placeholder bg-secondary rounded mx-auto" style="width:80%;height:60px;opacity:0.1;"></div>
          </div>
        </div>
      </aside>
    </div>
  </main>
  <!-- 🟥 頁尾標籤區（這段是你要求新增的） -->
  <div class="container py-3 border-top">
    <div class="text-center small">
      <?php
      // ⚠️ 此處是假資料，未來改成資料庫取出
      $footerTags = [
        ['title' => '刊登廣告', 'url' => '#'],
        ['title' => '聯絡我們', 'url' => '#'],
        ['title' => '自定義 3', 'url' => '#'],
        ['title' => '自定義 4', 'url' => '#'],
        ['title' => '自定義 5', 'url' => '#'],
      ];
      ?>

      <?php foreach ($footerTags as $i => $tag): ?>
        <a href="<?= $tag['url'] ?>" class="text-secondary text-decoration-none me-2">
          <?= $tag['title'] ?>
        </a>
        <?php if ($i < count($footerTags) - 1): ?>
          |
        <?php endif; ?>
      <?php endforeach; ?>
    </div>

  </div>
  <!-- 頁尾 -->
  <footer class="text-center text-secondary py-3 small border-top bg-white">
    © <?= date('Y') ?> 馨築新聞網 All Rights Reserved.
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>