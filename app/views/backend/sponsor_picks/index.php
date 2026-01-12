<div class="container-fluid">

    <div class="card shadow mb-4">

        <!-- 卡片 Header（標題 + 操作） -->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">廣告管理</h6>

        </div>

        <!-- 卡片 Body（內容） -->
        <div class="card-body">

            <form id="sponsorPickForm" method="post" action="?page=sponsorpicks_store">
                <?php if (canCreate(MODULE_SPONSORED)): ?>
                <label class="form-label mb-3 text-muted"> <small>（可使用拖曳 icon 變更順序）</small> </label>
                <button type="button" id="addRowBtn" class="btn btn-primary btn-sm">＋ 新增一筆</button>
                <?php endif; ?>

                <!-- sortable container -->
                <div id="sponsorPickList">

                    <?php if (empty($sponsorPicks)): ?>
                        <div class="text-center text-muted py-4 empty-row">
                            尚未建立任何廣告
                        </div>
                    <?php else: ?>
                        <?php foreach ($sponsorPicks as $sp): ?>

                            <!-- 🔹 一筆廣告（整組一起拖） -->
                            <div class="pick-item border rounded mb-1 p-2 bg-white">

                                <input type="hidden" name="id[]" value="<?= $sp['id'] ?>">

                                <!-- 欄位標題列 -->
                                <div class="pick-header d-flex gap-3 text-muted small mb-1 px-1">
                                    <div class="col-handle"></div>
                                    <div class="col-sort">順序</div>
                                    <div class="col-time">啟用時間</div>
                                    <div class="col-time">停用時間</div>
                                    <div class="col-category">新聞分類</div>
                                    <div class="col-article">連結文章</div>
                                    <div class="col-action"></div>
                                </div>

                                <!-- 主輸入列 -->
                                <div class="pick-row d-flex align-items-center gap-3">

                                    <!-- 拖曳 icon -->
                                    <div class="col-handle handle text-muted" style="cursor:grab;">
                                        <i class="fas fa-bars"></i>
                                    </div>

                                    <!-- sort -->
                                    <input type="text"
                                        class="form-control col-sort text-center bg-light"
                                        name="sort[]"
                                        value="<?= $sp['sort'] ?>"
                                        readonly>

                                    <!-- 啟用時間 -->
                                    <input type="datetime-local"
                                        class="form-control col-time"
                                        name="start_at[]"
                                        value="<?= date('Y-m-d\TH:i', strtotime($sp['start_at'])) ?>">

                                    <!-- 停用時間 -->
                                    <?php if (!empty($sp['end_at'])): ?>
                                        <input type="datetime-local"
                                            class="form-control col-time"
                                            name="end_at[]"
                                            value="<?= $sp['end_at'] ? date('Y-m-d\TH:i', strtotime($sp['end_at'])) : '' ?>">
                                    <?php else: ?>
                                        <div class="d-flex align-items-center gap-2 col-nonstop">
                                            <div class="form-control bg-light text-muted col-time">
                                                不停用
                                            </div>
                                            <button type="button"
                                                class="btn btn-outline-secondary btn-sm btn-set-end col-btnset">
                                                設定
                                            </button>
                                        </div>
                                        <input type="hidden" name="end_at[]" value="">
                                    <?php endif; ?>

                                    <!-- 分類 -->
                                    <select name="article_category_id[]"
                                        class="form-control col-category category-select"
                                        data-selected="<?= $sp['article_category_id'] ?>" required>
                                        <option value="">請選擇新聞分類</option>
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?= $cat['id'] ?>"
                                                <?= $cat['id'] == $sp['article_category_id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($cat['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>

                                    <!-- 文章 -->
                                    <select name="article_id[]"
                                        class="form-control col-article article-select" required>
                                        <option value="<?= $sp['article_id'] ?>">
                                            <?= htmlspecialchars($sp['article_title'] ?? '請重新選擇文章') ?>
                                        </option>
                                    </select>

                                    <!-- 操作 -->
                                    <div class="d-flex gap-1 col-action">
                                        <a href="index.php?page=news_show&id=<?= $sp['article_id'] ?>"
                                            target="_blank"
                                            class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if (canCreate(MODULE_SPONSORED)): ?>
                                        <button type="button"
                                            class="btn btn-outline-danger btn-sm deleteRowBtn">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- 補充資訊 -->
                                <div class="mt-2 small text-muted">
                                    ● 更新時間：<?= date('Y-m-d H:i', strtotime($sp['updated_at'])) ?>
                                    &nbsp;&nbsp;
                                    ● 廣告點擊次數：<?= (int)$sp['click_count'] ?>
                                    &nbsp;&nbsp;
                                    ● 文章內連結數：<?= (int)$sp['article_link_count'] ?>
                                </div>
                            </div>


                        <?php endforeach; ?>
                    <?php endif; ?>

                </div>
                <!-- 儲存 -->
                <div class="mt-4">
                    <?php if (canCreate(MODULE_SPONSORED)): ?>
                    <button type="submit" class="btn btn-success" id="btnSave">
                        儲存
                    </button>
                    <input type="hidden" name="action" value="update">
                    <?php endif; ?>
                </div>

            </form>

        </div>
    </div>
</div>



<script>
    window.categories = <?= json_encode($categories, JSON_UNESCAPED_UNICODE) ?>;

    // 初始化一次
    document.querySelectorAll('.category-select').forEach(select => {
        const selectedId = select.dataset.selected || '';
        renderCategoryOptions(select, selectedId);
    });

    // 新增列
    document.getElementById('addRowBtn').addEventListener('click', function() {
        const list = document.getElementById('sponsorPickList');
        const item = document.createElement('div');
        item.className = 'pick-item border rounded mb-3 p-3 bg-white';

        item.innerHTML = `
            <input type="hidden" name="id[]" value="">

            <!-- 欄位標題列 -->
            <div class="pick-header">
                <div class="col-handle"></div>
                <div class="col-sort">順序</div>
                <div class="col-time">啟用時間</div>
                <div class="col-time">停用時間</div>
                <div class="col-category">新聞分類</div>
                <div class="col-article">連結文章</div>
                <div class="col-action"></div>
            </div>

            <!-- 主輸入列 -->
            <div class="pick-row">

                <!-- 拖曳 -->
                <div class="col-handle handle text-muted" style="cursor: grab;">
                    <i class="fas fa-bars"></i>
                </div>

                <!-- sort -->
                <input type="text"
                    name="sort[]"
                    class="form-control col-sort text-center bg-light"
                    readonly>

                <!-- 啟用時間 -->
                <input type="datetime-local"
                    name="start_at[]"
                    class="form-control col-time">

                <!-- 停用時間 -->
                <input type="datetime-local"
                    name="end_at[]"
                    class="form-control col-time"
                    placeholder="不停用">

                <!-- 分類 -->
                <select name="article_category_id[]"
                    class="form-control col-category category-select" required>
                </select>

                <!-- 文章 -->
                <select name="article_id[]"
                    class="form-control col-article article-select" required>
                    <option value="">請先選擇文章</option>
                </select>

                <!-- 操作 -->
                <div class="col-action">
                    <button type="button"
                        class="btn btn-outline-danger btn-sm deleteRowBtn">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

            </div>
        `;

        list.appendChild(item);

        const empty = list.querySelector('.empty-row');
        if (empty) empty.remove();

        // 初始化分類
        renderCategoryOptions(item.querySelector('.category-select'));

        updateSortNumbers();
        isDataChanged = true;

    });

    // 刪除
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.deleteRowBtn')) return;

        const item = e.target.closest('.pick-item');
        const idInput = item.querySelector('input[name="id[]"]');
        const form = document.getElementById('sponsorPickForm');

        if (idInput && idInput.value) {
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'delete_ids[]';
            hidden.value = idInput.value;
            form.appendChild(hidden);
        }

        item.remove();
        updateSortNumbers();
        isDataChanged = true;

    });

    // 當分類改變時，載入對應文章
    document.addEventListener('change', function(e) {
        if (!e.target.classList.contains('category-select')) return;

        const categorySelect = e.target;
        const item = categorySelect.closest('.pick-item');
        const articleSelect = item.querySelector('.article-select');
        const categoryId = categorySelect.value;

        // 先清空文章下拉
        articleSelect.innerHTML = '<option value="">載入中...</option>';

        if (!categoryId) {
            articleSelect.innerHTML = '<option value="">請先選擇文章</option>';
            return;
        }

        fetch(`?page=api_sponsorpicks_article_by_category&category_id=${categoryId}`)
            .then(res => res.json())
            .then(articles => {
                let html = '<option value="">請選擇文章</option>';

                if (articles.length === 0) {
                    html = '<option value="">此分類沒有已發佈文章</option>';
                } else {
                    articles.forEach(a => {
                        html += `<option value="${a.id}">${a.title}</option>`;
                    });
                }

                articleSelect.innerHTML = html;
            })
            .catch(() => {
                articleSelect.innerHTML = '<option value="">載入失敗</option>';
            });
    });


    let isDirty = false;
    let isSorting = false;
    let isDataChanged = false;

    // 當輸入框有改變
    $(document).on('input', 'input[name="name[]"], input[name="sort[]"]', function() {
        isDirty = true;
    });

    // 當有拖曳動作
    $("#sponsorPickTableBody").on("sortupdate", function() {
        isDirty = true;
    });

    // 當使用者要關閉或重整頁面時提醒
    window.addEventListener('beforeunload', function(e) {
        if (isDirty) {
            e.preventDefault();
            e.returnValue = "您尚未儲存變更，確定要離開嗎？";
        }
    });

    // 當送出表單（儲存）時，重置旗標
    document.getElementById('sponsorPickForm').addEventListener('submit', function() {
        const actionInput = document.querySelector('input[name="action"]');
        if (isSorting && !isDataChanged) {
            actionInput.value = 'sort';
        } else {
            actionInput.value = 'update';
        }
        isDirty = false;
    });

    function updateSortNumbers() {
        document.querySelectorAll('#sponsorPickList .pick-item')
            .forEach((item, index) => {
                item.querySelector('input[name="sort[]"]').value = index + 1;

            });
    }

    $(function() {
        // 拖曳排序
        $("#sponsorPickList").sortable({
            axis: "y",
            cursor: "move",
            handle: ".handle",
            placeholder: "sortable-placeholder",
            items: ".pick-item",
            update: function(event, ui) {
                updateSortNumbers();
                isDirty = true;
                isSorting = true;

            }
        });
    });

    $(document).on('change input', 
        'input[name="start_at[]"], input[name="end_at[]"], select[name="article_category_id[]"], select[name="article_id[]"]',
        function () {
            isDataChanged = true;
        }
    );



    document.getElementById('sponsorPickForm').addEventListener('submit', function() {
        const sortInputs = document.querySelectorAll('input[name="sort[]"]');
        sortInputs.forEach((input, index) => {
            input.value = index + 1;
        });
    });

    function renderCategoryOptions(selectEl, selectedId = '') {
        let html = '<option value="">請選擇新聞分類</option>';

        window.categories.forEach(cat => {
            const selected = String(cat.id) === String(selectedId) ? 'selected' : '';
            html += `<option value="${cat.id}" ${selected}>${cat.name}</option>`;
        });

        selectEl.innerHTML = html;
    }

    document.querySelectorAll('.category-select').forEach(select => {
        renderCategoryOptions(select, select.dataset.selected);
    });
    document.addEventListener('click', function (e) {
        if (!e.target.classList.contains('btn-set-end')) return;

        const wrapper = e.target.closest('.d-flex');
        const hiddenInput = wrapper.nextElementSibling; // hidden end_at[]
        
        const input = document.createElement('input');
        input.type = 'datetime-local';
        input.name = 'end_at[]';
        input.className = 'form-control col-time';

        wrapper.replaceWith(input);
        hiddenInput.remove();
    });


</script>

<style>
    /* 拖曳佔位樣式 */
    .sortable-placeholder {
        background-color: #f0f0f0;
        height: 50px;
        border: 2px dashed #aaa;
    }

    .handle {
        cursor: move;
        color: #888;
        width: 30px;
        text-align: center;
    }

    .handle:hover {
        color: #333;
    }

    /* 拖曳icon亮起來 */
    tr:hover .handle {
        color: #007bff;
    }

    /* 點選該列時 整列顏色變化*/
    #sponsorPickTableBody tr:hover {
        background-color: #f8f9fa;
    }

    .pick-item {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 8px;
        background: #fff;
        margin-bottom: 8px;
    }

    /* 共用 row */
    .pick-header,
    .pick-row {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    /* 標題列 */
    .pick-header {
        font-size: 12px;
        color: #6b7280;
        margin-bottom: 4px;
    }

    /* 欄位寬度定義 */
    .col-handle {
        width: 40px;
        text-align: center;
    }

    .col-sort {
        width: 70px;
    }

    .col-time {
        width: 180px;
    }
    .col-nonstop {
        width: 180px;
        /* margin: 2px; */
    }
    .col-btnset {
        width:60px;
    }

    .col-category {
        width: 160px;
    }

    .col-article {
        flex: 1;
        /* ⭐ 唯一吃剩餘空間 */
        min-width: 200px;
        /* 不會被擠爆 */
        max-width: 420px;
        /* 不會無限長 */
    }

    .col-action {
        width: 70px;
        display: flex;
        gap: 4px;
        flex-shrink: 0
    }

    @media (max-width: 1200px) {
        .pick-header {
            display: none !important;
        }

        .pick-row {
            flex-wrap: wrap;
        }

        .col-time,
        .col-category,
        .col-article {
            width: 100%;
        }
    }

    @media (min-width: 1400px) {
    .pick-row {
        flex-wrap: nowrap;
    }
}

</style>