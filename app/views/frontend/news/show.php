    <?php
    // 動態生成 OG meta
    $ogImage = STATIC_URL . '/' . $article['cover_image'];

    $ogTags = "
    <meta property='og:title' content=\"".htmlspecialchars($article['title'])."\">
    <meta property='og:description' content=\"".htmlspecialchars(mb_substr(strip_tags($article['content_html']), 0, 80))."...\">
    <meta property='og:image' content=\"{$ogImage}\">
    <meta property='og:url' content=\"".BASE_URL."/?page=news_show&id={$article['id']}\">
    <meta property='og:type' content='article'>
    ";
    ?>
<div class="container my-4 article-container">

    <!-- 標題 -->
    <h1 class="fw-bold mb-4">
        <?= htmlspecialchars($article['title']) ?>
    </h1>

    <!-- 分類 + 日期 -->
    <div class="mb-3">
        <span class="badge bg-secondary px-3 py-2">
            <?= htmlspecialchars($categoryName) ?>
        </span>

        <?php
        $badgeClass = 'bg-secondary';
        if ($article['status'] === 'scheduled') $badgeClass = 'bg-warning';
        if ($article['status'] === 'published') $badgeClass = 'bg-success'; 
        ?>
        <span class="badge <?= $badgeClass ?> px-3 py-2">
            <?= $statusLabel ?>
        </span>

        <!-- <span class="text-muted ms-2">
            <i class="bi bi-clock"></i>
            <?= $article['publish_time'] ? date('Y/m/d H:i', strtotime($article['publish_time'])) : '-' ?>
        </span> -->


        <span class="text-muted ms-1">
            <i class="far fa-clock me-1"></i>
            <!-- <?= date('Y-m-d H:i', strtotime($article['publish_time'])) ?> -->
            <?= $article['publish_time'] ? date('Y/m/d H:i', strtotime($article['publish_time'])) : '-' ?>
        </span>

        <span class="text-muted ms-1">
            &nbsp&nbsp<i class="fas fa-solid fa-marker"></i>
            <?= htmlspecialchars($article['author']) ?>
        </span>
    </div>

    <!-- 主視覺圖（若有） 目前依據PM需求 不使用封面 -->
    <!-- <?php if (!empty($article['cover_image'])): ?> -->
        <!-- <div class="article-image-wrapper article-cover mb-3"> -->
            <!-- <img src="<?= BASE_URL . '/' . htmlspecialchars($article['cover_image']) ?>" -->
            <!-- <img src="<?= getCoverImage($article) ?>"
                class="img-fluid rounded shadow-sm article-image">
        </div> -->
        <!-- <?php if (!empty($article['images_caption'][0])): ?>
            <p class="text-muted small">
                ▲ <?= htmlspecialchars($article['images_caption'][0]) ?>
            </p> -->
        <!-- <?php endif; ?>
    <?php endif; ?> -->

    <!-- 內文（允許 HTML） -->
    <div class="article-content my-4">
        <?= $article['content_html'] ?>
    </div>

    <!-- 圖片區（若內文中沒有內嵌，可供 PM 使用） -->
    <!-- <?php if (!empty($article['images'])): ?>
        <?php foreach ($article['images'] as $idx => $img): ?>
            <?php if ($idx === 0) continue; // 第一張圖已當封面 
            ?>

            <div class="article-image-wrapper mb-3">
                <img src="<?= htmlspecialchars($img['url']) ?>"
                    class="img-fluid rounded shadow-sm article-image">
            </div>

            <?php if (!empty($img['caption'])): ?>
                <p class="text-muted small">
                    ▲ <?= htmlspecialchars($img['caption']) ?>
                </p>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?> -->

    <!-- 分享按鈕 -->
    <div class="my-5">
        <?php $shareUrl = BASE_URL . '/?page=news_show&id=' . $article['id'];
              $encodeUrl = rawurlencode($shareUrl); ?>
        <h6 class="fw-bold">分享文章</h6>

        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $encodeUrl ?>"
            target="_blank"
            class="btn btn-primary btn-sm me-2">
            FB 分享
        </a>
        <!-- <a href="https://www.facebook.com/sharer/sharer.php?u=https://www.apple.com"
            target="_blank"
            class="btn btn-primary btn-sm me-2">
            FB 分享
        </a> -->

        <a href="https://social-plugins.line.me/lineit/share?url=<?= $encodeUrl ?>"
            target="_blank"
            class="btn btn-success btn-sm">
            Line 分享
        </a>
        <!-- <a href="https://social-plugins.line.me/lineit/share?url=https://www.apple.com"
            target="_blank"
            class="btn btn-success btn-sm">
            Line 分享
        </a> -->
    </div>

</div>

<script>
    const articleId = <?= (int)$article['id'] ?>;

    function recordLinkClick(articleId, linkIndex) {
        const data = new URLSearchParams();
        data.append('id', articleId);
        data.append('index', linkIndex);
        navigator.sendBeacon(
            '<?= BASE_URL ?>/?page=api_link_click',
            data
        );
    }
    
</script>