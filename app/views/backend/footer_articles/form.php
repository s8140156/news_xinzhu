<!-- 新增/編輯文章 共用(頁尾標籤) -->
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 mb-4">

            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <!-- <h6 class="m-0 font-weight-bold text-primary">新增文章</h6> -->
                    <h6 class="m-0 font-weight-bold text-primary"><?= $mode=== 'edit' ? '編輯頁尾標籤文章' : '新增頁尾標籤文章' ?></h6>
                    <a href="<?= BASE_URL ?>/?page=footer_index" class="btn btn-secondary btn-sm" id="btnBack">返回列表</a>
                </div>

                <div class="card-body">
                    <form method="POST" action="<?= BASE_URL ?>/?page=footer_<?= $mode === 'edit' ? 'update' : 'store' ?>" enctype="multipart/form-data"
                        id="footerForm">
                        <input type="hidden" name="id" value="<?= $footerArticle['id'] ?>">

                        <!-- 第二行：標題 -->
                        <div class="form-group">
                            <label for="title" class="form-label"><span class="text-danger">*</span> 文章標題</label>
                            <input type="text" class="form-control" id="title" name="title" value="<?= $footerArticle['title'] ?>" placeholder="請輸入文章標題"
                                required>
                            <label for="author" class="form-label"><span class="text-danger">*</span> 作者</label>
                            <input type="text" class="form-control" id="author" name="author" value="<?= $footerArticle['author'] ?>" required>
                        </div>
                        <div class="form-group col-md-6">
                        </div>

                        <!-- 第三行：內容 -->
                        <div class="form-group">
                            <label for="editorContent" class="form-label"><span class="text-danger">*</span> 文章內容</label>
                            <textarea class="form-control" id="editorContent" name="editorContent" rows="10"><?= $footerArticle['content'] ?></textarea>
                        </div>


                        <!-- 只有編輯模式才顯示連結點擊區塊 -->
                        <?php if ($mode === 'edit'): ?>
                        <div class="mt-4 mb-3">
                            <label class="form-label fw-semibold text-secondary mb-2">連結點擊次數：</label>

                            <?php
                            // 連結text
                            $links = [];
                            if (!empty($footerArticle['links']) && is_string($footerArticle['links'])) {
                                $decoded = json_decode($footerArticle['links'], true);
                                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                    $links = $decoded;
                                }
                            }
                            // 連結點擊數
                            $linkClicks = [];
                            if (!empty($footerArticle['link_clicks'])) {
                                $decoded = json_decode($footerArticle['link_clicks'], true);
                                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                    $linkClicks = $decoded;
                                }

                            }
                            ?>

                            <?php if (!empty($links)): ?>
                                <div class="border rounded px-3 py-2 small">
                                    <?php foreach ($links as $idx => $link): ?>
                                        <div class="d-flex align-items-center mb-1">
                                            <div class="">
                                                <span class="text-muted">連結 <?= $idx + 1 ?>：</span>
                                                <span class="fw-medium link-display"><?= htmlspecialchars($link['text'] ?: '') ?></span>
                                            </div>
                                            <div class="text-muted ms-3">
                                                點擊數：<?= $linkClicks[$idx] ?? 0 ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted small fst-italic">(此文章沒有附加連結)</p>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <div class="schedule-actions">
    
                            <!-- 排程按鈕 -->
                            <div class="d-flex flex-wrap align-items-center ml-4" style="gap: 0.5rem;">
                                <input type="hidden" name="action" value="draft|schedule|publish">
                                <?php if ($mode === 'edit' && $footerArticle['status'] === 'published'): ?>
                                <button type="submit" name="action" value="" class="btn btn-info text-white" <?= $mode=== 'edit' ? '' : 'style="display:none;"' ?>>
                                    更新文章
                                </button>
                                <?php else: ?>
                                <button type="submit" name="action" value="publish" class="btn btn-success">
                                    發布
                                </button>
                                <button type="submit" name="action" value="draft" class="btn btn-warning text-white">
                                    儲存(草稿)
                                </button>
                                <?php endif ?>
                            </div>

                        </div> <!-- schedule-actions end -->
                    </form>
                </div> <!-- card-body end -->
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 等 DOM 準備好後初始化 CKEditor
    if (document.getElementById('editorContent')) {

        // 防止吃掉母版HTML
        CKEDITOR.disableAutoInline = true;

        // 初始化 CKEditor
        const editor = CKEDITOR.replace('editorContent', {
            height: 400,
            extraPlugins: 'image2,widget', // 增加圖說功能
            removePlugins: 'elementpath,image', // 移除底部狀態列
            resize_enabled: false, // 禁止調整大小
            toolbar: [
                { name: 'document', items: ['Source', '-', 'Preview', '-', 'Templates'] },
                { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'Undo', 'Redo'] },
                { name: 'editing', items: ['Find', 'Replace', '-', 'SelectAll'] },
                        '/',
                { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', '-', 'Subscript', 'Superscript', '-', 'RemoveFormat'] },
                { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
                        '/',
                { name: 'styles', items: ['Font', 'FontSize'] },
                { name: 'colors', items: ['TextColor', 'BGColor'] },
                { name: 'insert', items: ['Image', 'Table', 'HorizontalRule', 'SpecialChar', 'Link', 'Unlink', 'Smiley'] },
                { name: 'tools', items: ['Maximize'] }
            ],
            font_names:
                'Arial/Arial, Helvetica, sans-serif;' +
                'Comic Sans MS/Comic Sans MS, cursive;' +
                'Courier New/Courier New, monospace;' +
                'Georgia/Georgia, serif;' +
                'Lucida Sans Unicode/Lucida Sans Unicode, Lucida Grande, sans-serif;' +
                'Tahoma/Tahoma, Geneva, sans-serif;' +
                'Times New Roman/Times New Roman, Times, serif;' +
                'Trebuchet MS/Trebuchet MS, Helvetica, sans-serif;' +
                'Verdana/Verdana, Geneva, sans-serif;',
            fontSize_sizes:
                '10/10px;12/12px;14/14px;16/16px;18/18px;20/20px;24/24px;30/30px;36/36px;',
            filebrowserUploadMethod: 'form',
            filebrowserUploadUrl: '<?= BASE_URL ?>/?page=footer_image_upload&id=<?= $footerArticle['id'] ?? "temp" ?>',
            filebrowserImageUploadUrl: '<?= BASE_URL ?>/?page=footer_image_upload&id=<?= $footerArticle['id'] ?? "temp" ?>',
            forceAbsoluteUrl: true,


            // 圖說設定
            image2_alignClasses: ['image-align-left', 'image-align-center', 'image-align-right'],
            image2_captionedClass: 'image-captioned',
            image_prefillDimensions: false,
            image2_disableResizer: false
        });

        // 圖片刪除偵測及上限檢查
        editor.on('instanceReady', function() {
            console.log('CKEditor loaded without visible warning.'); // 可選：確認載入成功

            let deletedImages = [];
            let lastImageList = [];

            // 取得目前img src清單
            function getImageSrcList () {
                const html = editor.getData();
                const div = document.createElement('div');
                div.innerHTML = html;
                return Array.from(div.querySelectorAll('img')).map(img => img.getAttribute('src'));
            }
            lastImageList = getImageSrcList();

            // 偵測使用者內容變化
            editor.on('change', function() {
                const currentImageList = getImageSrcList();

                // 找出被刪除的圖片
                lastImageList.forEach(src => {
                    if (!currentImageList.includes(src) && !deletedImages.includes(src)) {
                        deletedImages.push(src);
                        console.log('Image deleted:', src); // 可選：確認刪除的圖片
                    }
                });

                // 更新最後圖片清單
                lastImageList = currentImageList;
            });
            // 監聽送出按鈕
            const form = document.querySelector('#articleForm');
            form.addEventListener('submit', function(event) {
                const currentList = getImageSrcList();

                // 判斷是否超過5張
                if (currentList.length > 5) {
                    event.preventDefault(); // 阻止表單提交
                    alert('文章內容中的圖片數量不可超過 5 張，請刪減後再提交。');
                    return false;
                }
                // 寫入刪除清單到hidden input
                let hiddenInput = form.querySelector('input[name="deleted_images"]');
                if (!hiddenInput) {
                    hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'deleted_images';
                    form.appendChild(hiddenInput);
                }
                hiddenInput.value = JSON.stringify(deletedImages);
                console.log('Deleted images to submit:', deletedImages); // 可選：確認送出的刪除清單
            });
        });
    }
});

</script>

<style>
.schedule-actions {
  display: flex;
  align-items: flex-end;
  gap: 10px;
  flex-wrap: wrap; /* 手機時可自動換行 */
}

.schedule-actions .form-control {
  max-width: 160px;
}

/* 連結灰底框 */
.link-display {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    padding: 2px 6px;
    display: inline-block;
    min-width: 150px;
    color: #333;
    margin-left: 4px;  /* ✅ 與「連結 X：」之間微距 */
    margin-right: 10px;
    vertical-align: middle;  /* ✅ 與點擊數垂直居中對齊 */
}


</style>