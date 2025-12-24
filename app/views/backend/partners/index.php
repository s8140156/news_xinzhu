<div class="container-fluid">

    <div class="card shadow mb-4">

        <!-- Header -->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">合作媒體管理</h6>
        </div>

        <!-- Body -->
        <div class="card-body">

            <form id="partnerForm" method="post" action="?page=partner_store" enctype="multipart/form-data">
                <label class="form-label mb-3">（可使用拖曳 icon 變更順序）</label>
                <button type="button" id="addRowBtn" class="btn btn-primary btn-sm mb-3">＋ 新增一筆</button>

                <!-- 拖曳區塊id="partnerList" -->
                <div class="" id="partnerList">
                    <?php if (empty($partners)): ?>
                        <div class="text-center text-muted py-4 empty-row">
                            尚未建立任何合作媒體
                        </div>
                    <?php else: ?>
                        <?php foreach ($partners as $p): ?>

                            <!-- 原格式start -->
                            <div class="partner-item border rounded mb-2 p-2 bg-white">

                                <input type="hidden" name="id[]" value="<?= $p['id'] ?>">

                                <!-- 欄位標題 -->
                                <div class="pick-header d-flex gap-3 text-muted small mb-1 px-1">
                                    <div class="header-left">
                                        <div class="col-handle"></div>
                                        <div class="col-sort">順序</div>
                                        <div class="col-image">新增圖片</div>
                                    </div>
                                    <div class="col-action"></div>
                                </div>

                                <!-- 主輸入列 -->
                                <div class="pick-row d-flex align-items-center gap-3">

                                    <!-- 拖曳 -->
                                    <div class="col-handle handle text-muted">
                                        <i class="fas fa-bars"></i>
                                    </div>

                                    <!-- sort -->
                                    <input type="text"
                                        name="sort[]"
                                        class="form-control col-sort text-center bg-light"
                                        value="<?= $p['sort'] ?>"
                                        readonly>

                                    <!-- 圖片 -->
                                    <div class="form-group partner-image">
                                        <input type="file" class="form-control mb-2" id="image" name="image[]" value="" accept="image/*">
                                        <?php if (!empty($p['image'])): ?>
                                            <div class="partner-image-preview partner-thumb">
                                                <img src="<?= STATIC_URL . '/' . $p['image'] ?>" alt="目前封面圖片" style="width:150px; height:auto; border:1px solid #ddd; padding:4px;">
                                                <p class="text-muted image-filename partner-filename" style="font-size: 0.9em;">封面檔案：<?= basename($p['image']) ?></p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="partner-info">

                                        <div class="partner-time-row">
                                            <div class="col-time partner-label">啟用 / 停用時間</div>
                                            <div class="partner-time-inputs">
                                                <!-- 啟用時間 -->
                                                <input type="datetime-local"
                                                    name="start_at[]"
                                                    class="form-control col-time"
                                                    value="<?= date('Y-m-d\TH:i', strtotime($p['start_at'])) ?>">
            
                                                <!-- 停用時間 -->
                                                <?php if (!empty($p['end_at'])): ?>
                                                    <input type="datetime-local"
                                                        name="end_at[]"
                                                        class="form-control col-time"
                                                        value="<?= date('Y-m-d\TH:i', strtotime($p['end_at'])) ?>">
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

                                            </div>
                                        </div>
                                        <div class="partner-link-row">
                                        <div class="col-link">連結路徑</div>

                                            <!-- 連結 -->
                                            <input type="url"
                                                name="link_url[]"
                                                class="form-control col-link"
                                                value="<?= htmlspecialchars($p['link_url']) ?>">
                                        </div>

                                        <!-- 補充資訊 -->
                                        <div class="mt-2 small text-muted">
                                            ● 點擊次數：<?= (int)$p['click_count'] ?>
                                        </div>

                                    </div>


                                    <!-- 操作 -->
                                    <div class="d-flex gap-1 col-action">
                                        <a href="<?= $p['link_url'] ?>"
                                            target="_blank"
                                            class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                        <button type="button"
                                            class="btn btn-outline-danger btn-sm deleteRowBtn">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>

                            </div>

                            <!-- 原格式end -->
                        <?php endforeach; ?>
                    <?php endif; ?>

                </div>
                <!-- 儲存 -->
                <div class="mt-4">
                    <button type="submit" class="btn btn-success">
                        儲存
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.getElementById('addRowBtn').addEventListener('click', function() {

        const list = document.getElementById('partnerList');

        const item = document.createElement('div');
        item.className = 'partner-item border rounded mb-2 p-2 bg-white';

        item.innerHTML = `
            <input type="hidden" name="id[]" value="">

            <!-- 欄位標題 -->
            <div class="pick-header d-flex gap-3 text-muted small mb-1 px-1">
                <div class="col-handle"></div>
                <div class="col-sort">順序</div>
                <div class="col-image">新增圖片</div>
                <div class="col-time">啟用時間</div>
                <div class="col-time">停用時間</div>
                <div class="col-link">連結路徑</div>
                <div class="col-action"></div>
            </div>

            <!-- 主輸入列 -->
            <div class="pick-row d-flex align-items-center gap-3">

                <!-- 拖曳 -->
                <div class="col-handle handle text-muted" style="cursor: grab;">
                    <i class="fas fa-bars"></i>
                </div>

                <!-- sort -->
                <input type="text"
                    name="sort[]"
                    class="form-control col-sort text-center bg-light"
                    readonly>

                <!-- 圖片 -->
                <div class="form-group">
                    <input type="file"
                        class="form-control mb-2"
                        name="image[]"
                        accept="image/*">
                </div>

                <!-- 啟用時間 -->
                <input type="datetime-local"
                    name="start_at[]"
                    class="form-control col-time">

                <!-- 停用時間（預設不停用） -->
                <input type="datetime-local"
                    name="end_at[]"
                    class="form-control col-time"
                    placeholder="不停用">

                <!-- 連結 -->
                <input type="url"
                    name="link_url[]"
                    class="form-control col-link"
                    placeholder="https://">

                <!-- 操作 -->
                <div class="d-flex gap-1 col-action">
                    <button type="button"
                            class="btn btn-outline-danger btn-sm deleteRowBtn">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        list.appendChild(item);

        // 移除空資料提示
        const empty = list.querySelector('.empty-row');
        if (empty) empty.remove();

        updateSortNumbers();

    });
    // 刪除
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.deleteRowBtn')) return;

        const item = e.target.closest('.partner-item');
        const idInput = item.querySelector('input[name="id[]"]');
        const form = document.getElementById('partnerForm');

        if (idInput && idInput.value) {
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'delete_ids[]';
            hidden.value = idInput.value;
            form.appendChild(hidden);
        }

        item.remove();
        updateSortNumbers();
    });

    function updateSortNumbers() {
        document.querySelectorAll('#partnerList .partner-item')
            .forEach((item, index) => {
                const sortInput = item.querySelector('input[name="sort[]"]');
                if (sortInput) {
                    sortInput.value = index + 1;
                }
            });
    }

    $("#partnerList").sortable({
        axis: "y",
        cursor: "move",
        handle: ".handle",
        placeholder: "sortable-placeholder",
        items: ".partner-item",
        update: function() {
            updateSortNumbers();
            isDirty = true;
        }
    });

    document.addEventListener('click', function(e) {
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
        cursor: grab;
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
    }

    /* 標題列 */
    .pick-header {
        font-size: 12px;
        color: #6b7280;
        margin-bottom: 4px;
    }

    /* 欄位寬度定義 */
    .col-handle {
        width: 45px;
        text-align: center;
    }

    .col-sort {
        width: 70px;
    }

    .col-time {
        width: 200px;
    }

    .col-nonstop {
        width: 200px;
        /* margin: 2px; */
    }

    .col-btnset {
        width: 65px;
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
    }

    @media (max-width: 1200px) {
        .pick-header {
            display: none;
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
    .partner-image-preview {
    display: flex;
    flex-direction: column;   /* ← 關鍵 */
    align-items: center;      /* 水平置中 */
    gap: 4px;
    margin-top: 6px;
}

.partner-image-preview img {
    width: 120px;             /* 可依你版面調 */
    height: auto;
    border: 1px solid #ddd;
    padding: 4px;
    background: #fff;
}

.image-filename {
    font-size: 12px;
    color: #6c757d;
    text-align: center;
    word-break: break-all;    /* 避免檔名太長爆版 */
}

/* 右側整包 */
.partner-info {
    display: flex;
    flex-direction: column;
    gap: 8px;
    flex: 1;              /* 吃掉中間空間 */
    min-width: 360px;
}

/* 時間那一排：左右 */
.partner-time-row {
    display: flex;
    flex-direction: column;
    gap: 6px;
}
/* 上方標題 */
.partner-label {
    font-size: 12px;
    color: #6b7280;
}

/* inputs 本身 */
.partner-time-inputs {
    display: flex;
    gap: 8px;
    align-items: center;
}

/* 網址列 */
.partner-link-row input {
    width: 100%;
}

/* 點擊次數 */
.partner-click {
    font-size: 12px;
    color: #6c757d;
    padding-left: 4px;
}

/* 圖片整個區塊：自己直排 */
.partner-image {
    display: flex;
    flex-direction: column;
    align-items: flex-start; /* ⭐ 向左對齊 */
    gap: 6px;
}

/* 縮圖區 */
.partner-thumb {
    display: flex;
    flex-direction: column;
    align-items: flex-start; /* ⭐ */
    gap: 4px;
}

/* 縮圖本身 */
.partner-thumb img {
    width: 120px;
    height: auto;
    border: 1px solid #ddd;
    padding: 4px;
    background: #fff;
}

/* 檔名文字 */
.partner-filename {
    font-size: 12px;
    color: #6c757d;
    word-break: break-all;
}

.pick-header {
    display: flex;
    gap: 12px;
    font-size: 12px;
    color: #6b7280;
    margin-bottom: 6px;
}

/* 左邊：跟 handle / sort / image 對齊 */
.header-left {
    display: flex;
    gap: 12px;
}

/* 中間：跟 partner-info 對齊 */
.header-middle {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

/* 右邊：操作 */
.header-action {
    width: 70px;
}








</style>