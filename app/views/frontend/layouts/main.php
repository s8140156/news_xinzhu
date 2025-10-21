<!DOCTYPE html>
<html lang="zh-Hant">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $title ?? '馨築新聞網' ?></title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= BASE_URL ?>/assets/css/frontend.css" rel="stylesheet">

</head>
<body class="bg-light">

<!-- 導覽列 -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold text-primary" href="<?= BASE_URL ?>/index.php">馨築新聞網</a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php
        foreach($categories as $cat): ?>
          <li class="nav-item"><a class="nav-link" href="#"><?= $cat['name'] ?></a></li>
        <?php endforeach; ?>
      </ul>

      <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="搜尋新聞...">
        <button class="btn btn-outline-primary">搜尋</button>
      </form>
    </div>
  </div>
</nav>

<!-- 主內容區 -->
<main class="container my-4">
  <?php include $content; ?>
</main>

<!-- 頁尾 -->
<footer class="text-center text-secondary py-3 small border-top">
  © <?= date('Y') ?> 馨築新聞網 All Rights Reserved.
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
