<?php
// app/views/backend/errors/403.php
// 你可以從 controller 傳 $message / $code / $traceId 之類，但預設保持簡潔
$title = $title ?? '403 權限不足';
$message = $message ?? '你沒有權限存取此頁面，請確認你的角色權限，或聯絡系統管理員。';
// $backUrl = $backUrl ?? ($_SERVER['HTTP_REFERER'] ?? BASE_URL . '?page=backend_dashboard');
$homeUrl = $homeUrl ?? (BASE_URL . '?page=article_index');
?>

<div class="error-page">
  <div class="error-card">
    <div class="error-icon" aria-hidden="true">
      <!-- FontAwesome / Bootstrap icons 你專案用哪個就換哪個 -->
      <i class="fas fa-ban"></i>
    </div>

    <!-- <div class="error-code">403</div> -->
    <h1 class="error-title"><?= htmlspecialchars($title) ?></h1>
    <p class="error-desc"><?= htmlspecialchars($message) ?></p>

    <div class="error-actions">
      <!-- <a class="btn btn-outline-secondary" href="<?= htmlspecialchars($backUrl) ?>">
        <i class="fas fa-arrow-left me-1"></i> 回上一頁
      </a> -->
          <button type="button"
            class="btn btn-outline-secondary"
            onclick="history.back();">
                ← 回上一頁
            </button>

      <a class="btn btn-primary" href="<?= htmlspecialchars($homeUrl) ?>">
        <i class="fas fa-house me-1"></i> 回後台首頁
      </a>
    </div>

    <div class="error-hint">
      若你覺得應該要有權限，請聯絡 Super Admin 協助調整。
    </div>
  </div>
</div>
