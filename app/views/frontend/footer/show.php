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
<style>
    .article-cover {
        width: 100%;
        height: 500px;
        /* 或用 aspect-ratio */
        overflow: hidden;
    }

    .article-cover img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        /* 把容器填滿、會裁切 */
        object-position: center;
        /* 確保是以「中心點」裁切 */
    }

    /* CKEditor 內文的統一排版樣式 */
    .article-content img {
        display: block;
        max-width: 100%;
        height: auto;
        margin: 1.2rem auto;
        border-radius: 6px;
    }

    .article-content p {
        line-height: 1.8;
        margin-bottom: 1rem;
    }

    .article-content a {
        color: #007bff;
        text-decoration: underline;
    }

    .article-content figure {
        margin: 1.5rem auto;
        text-align: center;
    }

    .article-content figcaption {
        font-size: 0.9rem;
        color: #888;
        margin-top: 0.5rem;
    }

    .article-content figcaption::before {
        content: "▲ ";
        color: #666;
    }

    /* 如果 CKEditor 產出的圖片是 inline-block，也強制成 block */
    .article-content img {
        display: block !important;
    }
</style>