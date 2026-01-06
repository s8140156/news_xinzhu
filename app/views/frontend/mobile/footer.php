<?php
// =============================
// 手機版：頁碼標籤內文
// =============================
?>

<section class="mobile-section article-footer-section">

  <h3 class="section-title mb-3">
    <?= htmlspecialchars($footer['title']) ?>
  </h3>

  <!-- （可選）作者 / 時間 -->
  <div class="mb-3 text-muted small">
    <i class="far fa-clock me-1"></i>
    最後更新時間：<?= date('Y/m/d H:i', strtotime($footer['created_at'])) ?>

    <span class="ms-2">
      <i class="fas fa-solid fa-marker"></i>
      <?= htmlspecialchars($footer['author']) ?>
    </span>
  </div>

  <!-- 內文（允許 HTML） -->
  <div class="article-content my-4">
    <?= $footer['content_html'] ?? $footer['content'] ?>
  </div>

  </div>

</section>

<script>
  const footerId = <?= (int)$footer['id'] ?>;

  function recordLinkClick(footerId, linkIndex) {
    const data = new URLSearchParams();
    data.append('id', footerId);
    data.append('index', linkIndex);

    navigator.sendBeacon(
      '<?= BASE_URL ?>/?page=api_footer_link_click',
      data
    );
  }
</script>