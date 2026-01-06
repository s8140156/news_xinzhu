<div class="container my-4 article-container">

    <!-- 標題 -->
    <h1 class="fw-bold mb-4">
        <?= htmlspecialchars($footer['title']) ?>
    </h1>

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
<script>
    const footerId = <?= (int)$footer['id'] ?>;

    function recordLinkClick(footerId, linkIndex) {
        console.log("footerId:", footerId);
        console.log("index = ", linkIndex);
        fetch("<?= BASE_URL ?>/?page=api_footer_link_click", {
                method: "POST",
                headers: {
                    "Content-type": "application/x-www-form-urlencoded"
                },
                body: `id=${footerId}&index=${linkIndex}`
            })
            .then(res => res.text())
            .then(console.log)
            .catch(console.error);
    }
</script>
