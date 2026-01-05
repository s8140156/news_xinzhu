<?php
// =============================
// 手機版：文章內文
// =============================

// OG meta
$ogImage = !empty($article['cover_image'])
  ? STATIC_URL . '/' . $article['cover_image']
  : '';

$ogTags = "
<meta property='og:title' content=\"" . htmlspecialchars($article['title']) . "\">
<meta property='og:description' content=\"" . htmlspecialchars(mb_substr(strip_tags($article['content_html']), 0, 80)) . "...\">
<meta property='og:image' content=\"{$ogImage}\">
<meta property='og:url' content=\"" . BASE_URL . "/?page=news_show&id={$article['id']}\">
<meta property='og:type' content='article'>
";
?>

<section class="mobile-section article-show-section">

  <h3 class="section-title mb-3">
    <?= htmlspecialchars($article['title']) ?>
  </h3>

  <!-- 分類 / 日期 / 作者 -->
  <div class="mb-3 text-muted small">
    <span class="badge bg-secondary me-2">
      <?= htmlspecialchars($categoryName) ?>
    </span>
    <?php
    $badgeClass = 'bg-secondary';
    if ($article['status'] === 'scheduled') $badgeClass = 'bg-warning';
    if ($article['status'] === 'published') $badgeClass = 'bg-success';
    ?>
    <span class="badge <?= $badgeClass ?>">
      <?= $statusLabel ?>
    </span>

    <i class="far fa-clock me-1"></i>
    <?= $article['publish_time']
      ? date('Y/m/d H:i', strtotime($article['publish_time']))
      : '-' ?>

    <span class="ms-2">
      <i class="fas fa-user-edit me-1"></i>
      <?= htmlspecialchars($article['author']) ?>
    </span>
  </div>

  <!-- 內文 -->
  <div class="article-content my-4">
    <?= $article['content_html'] ?>
  </div>

  <!-- 分享 -->
  <div class="my-5">
    <?php
    $shareUrl  = BASE_URL . '/?page=news_show&id=' . $article['id'];
    $encodeUrl = rawurlencode($shareUrl);
    ?>
    <h6 class="fw-bold mb-2">分享文章</h6>

    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $encodeUrl ?>"
      target="_blank"
      class="btn btn-primary btn-sm me-2">
      FB 分享
    </a>

    <a href="https://social-plugins.line.me/lineit/share?url=<?= $encodeUrl ?>"
      target="_blank"
      class="btn btn-success btn-sm">
      LINE 分享
    </a>
  </div>

</section>