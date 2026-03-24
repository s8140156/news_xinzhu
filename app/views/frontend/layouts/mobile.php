<!DOCTYPE html>
<html lang="zh-Hant">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>馨築生活</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= STATIC_URL ?>/assets/backend/vendor/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="<?= STATIC_URL ?>/assets/frontend/css/style.css">
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
            <section class="highlight-section mb-4" id="highlight-marquee" style="display:none">
                <!-- <h5 class="fw-bold text-secondary border-bottom pb-2">焦點即時</h5> -->
                <h5 class="fw-bold text-secondary border-bottom pb-2"><?= $siteTitles['home_right_top_title'] ?? '' ?></h5>
                <div class="marquee bg-light rounded p-3 small text-muted">
                    <ul class="marquee-inner marquee-list" id="highlight-marquee-inner">

                    </ul>
                </div>
            </section>
        <?php endif; ?>

        <!-- 🔸 焦點新聞 -->
        <?php if (!empty($focusArticle)): ?>
            <section class="focus-section mb-4">
                <h5 class="fw-bold text-secondary border-bottom pb-2"><?= $focusCategory['name'] ?? '' ?></h5>
                <!-- <h5 class="fw-bold text-secondary border-bottom pb-2"><?= $siteTitles['home_right_middle_title'] ?? '' ?></h5> -->
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
        <div class="mobile-content">
            <?php include $mobileContent; ?>
        </div>

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

    <footer class="text-center text-secondary py-3 small border-top bg-white">
        <p class="mb-1">
            總社信箱：
            <a href="mailto:hclife.news@gmail.com">hclife.news@gmail.com</a>
        </p>
        <p class="mb-1">馨築生活資訊媒體事業</p>
        <p class="mb-0">版權所有 未經同意請勿轉載</p>
    </footer>

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