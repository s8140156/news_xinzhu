<!DOCTYPE html>
<html lang="zh-Hant">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>馨築生活</title>

    <link href="<?= BASE_URL ?>/assets/frontend/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <!-- 🔹 Navbar（共用） -->
    <nav class="navbar navbar-light bg-white shadow-sm sticky-top">
        <div class="container d-flex align-items-center gap-2">

            <!-- Logo -->
            <a class="navbar-brand fw-bold text-primary m-0" href="<?= BASE_URL ?>">
                馨築生活
            </a>

            <!-- 🔍 搜尋（在選單裡） -->
            <form class="mobile-search flex-grow-1 d-flex" method="get">
                <input type="hidden" name="page" value="search">
                <div class="input-group">
                    <input type="search"
                        name="keyword"
                        class="form-control"
                        placeholder="搜尋新聞..."
                        required>
                    <button class="btn btn-outline-primary btn-sm" type="submit">搜尋</button>
                </div>
            </form>

            <!-- 漢堡 -->
            <button class="navbar-toggler navbar-toggler-sm"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mobileMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

        </div>

        <!-- 手機展開選單 -->
        <div class="collapse" id="mobileMenu">
            <div class="container py-3">

                <!-- 分類 -->
                <ul class="list-unstyled">
                    <?php foreach ($categories as $id => $name): ?>
                        <li class="py-2 border-radius">
                            <a class="text-decoration-none text-dark"
                                href="<?= BASE_URL ?>/?page=news_list&category=<?= $id ?>">
                                <?= htmlspecialchars($name) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>

            </div>
        </div>
    </nav>


    <main class="container my-3">

        <!-- 🔸 廣告區 -->
        <?php if (!empty($categories)): ?>
            <section class="mb-4">
                <h5 class="fw-bold text-secondary border-bottom pb-2">廣告</h5>
                <div class="bg-light rounded p-3 small text-muted">
                    廣告內容（之後接跑馬燈）
                </div>
            </section>
        <?php endif; ?>

        <!-- 🔸 焦點新聞 -->
        <?php if (!empty($focusArticle)): ?>
            <section class="mb-4">
                <h5 class="fw-bold text-secondary border-bottom pb-2">焦點新聞</h5>
                <div class="card border-0 shadow-sm">
                    <img src="<?= getCoverImage($focusArticle) ?>" class="card-img-top">
                    <div class="card-body">
                        <a href="<?= BASE_URL ?>/?page=news_list&category=<?= $focusArticle['category_id'] ?>"
                            class="btn btn-primary btn-sm">
                            閱讀更多
                        </a>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- 🔸 手機主內容 -->
        <?php include $mobileContent; ?>

        <!-- 🔸 合作媒體 -->
        <?php if (!empty($partners)): ?>
            <section class="partner-section card-style my-4">
                <h5 class="fw-bold text-secondary border-bottom pb-2">合作媒體</h5>
                <div class="bg-light p-1 rounded text-center">
                    <?php foreach ($partners as $p): ?>
                        <a
                            href="<?= BASE_URL ?>/?page=api_partner_click&id=<?= $p['id'] ?>"
                            target="_blank"
                            class="partner-item">
                            <div class="partner-logo mb-3">
                                <img src="<?= STATIC_URL . '/' . $p['image'] ?>" alt="合作媒體">
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

    </main>

    <!-- Footer -->
    <div class="container text-center small text-secondary py-3">
        <?php foreach ($footerTags as $i => $tag): ?>
            <a href="?page=news_footer_show&id=<?= $tag['id'] ?>" class="text-decoration-none text-secondary">
                <?= $tag['title'] ?>
            </a>
            <?= $i < count($footerTags) - 1 ? ' | ' : '' ?>
        <?php endforeach; ?>
    </div>

    <footer class="text-center small text-secondary py-3 border-top bg-white">
        © <?= date('Y') ?> 馨築新聞網
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>