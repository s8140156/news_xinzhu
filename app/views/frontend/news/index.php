<!-- <h1>我是首頁~</h1> -->
<section class="news-categories py-4">
    <div class="container">
        <div class="row">
            <?php foreach ($categories as $cat): ?>
            <div class="col-md-3 mb-4">
                <div class="news-card">
                    <div class="img-wrap">
                        <img src="path/to/default.jpg" alt="<?= htmlspecialchars($cat['name']) ?>">
                        <div class="overlay">
                            <span><?= htmlspecialchars($cat['name']) ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>