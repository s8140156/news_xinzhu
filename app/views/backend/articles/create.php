<!-- <h1>我是文章新增</h1> -->
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 mb-4">

            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">新增文章</h6>
                    <a href="<?= BASE_URL ?>/?page=article_index" class="btn btn-secondary btn-sm" id="btnBack">返回列表</a>
                </div>

                <div class="card-body">
                    <form method="POST" action="<?= BASE_URL ?>/?page=article_store" enctype="multipart/form-data"
                        id="articleForm">

                        <!-- 第一行：分類 + 作者 -->
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="category" class="form-label">分類</label>
                                <select class="form-control" id="category" name="category_id" required>
                                    <option value="">請選擇分類</option>
                                    <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="author" class="form-label">作者</label>
                                <input type="text" class="form-control" id="author" name="author" value="" required>
                            </div>
                        </div>

                        <!-- 第二行：標題 -->
                        <div class="form-group">
                            <label for="title" class="form-label">文章標題</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="請輸入文章標題"
                                required>
                        </div>

                        <!-- 第三行：內容 -->
                        <div class="form-group">
                            <label for="editorContent" class="form-label">文章內容</label>
                            <textarea class="form-control" id="editorContent" name="editorContent" rows="10"></textarea>
                        </div>

                        <!-- 第四行：封面圖片（可選） -->
                        <div class="form-group">
                            <label for="image" class="form-label">封面圖片（可選）</label>
                            <input type="file" class="form-control" id="image" name="cover_image" accept="image/*">
                            <small class="form-text text-muted">
                                若文章內已有圖片，將自動抓取第一張作為封面；否則使用此上傳圖片。
                            </small>
                        </div>

                        <!-- 第五行：排程發布 -->
                        <!-- <div class="form-row align-items-end">
                            <div class="form-group col-md-6">
                                <label for="publish_date">排程發布日期</label>
                                <input type="date" class="form-control" id="publish_date" name="publish_date">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="publish_time">排程發布時間</label>
                                <input type="time" class="form-control" id="publish_time" name="publish_time">
                            </div>
                        </div> -->

                        <!-- 操作按鈕區 -->
                        <!-- <div class="text-right mt-4 d-flex flex-wrap justify-content-end gap-2">
                            <button type="submit" name="action" value="draft" class="btn btn-secondary">暫不發布</button>
                            <button type="submit" name="action" value="schedule"
                                class="btn btn-warning text-dark">排程發布</button>
                            <button type="submit" name="action" value="publish" class="btn btn-success">立即發布</button>
                        </div> -->

                        <div class="form-group d-flex flex-wrap align-items-center mt-3" style="gap: 0.75rem;">

                            <!-- 排程日期 -->
                            <div class="d-flex align-items-center">
                                <label for="schedule_date" class="mr-2 mb-0">排程日期</label>
                                <input type="date" id="schedule_date" name="schedule_date" class="form-control"
                                    style="width: 160px;">
                            </div>

                            <!-- 排程時間 -->
                            <div class="d-flex align-items-center ml-3">
                                <label for="schedule_time" class="mr-2 mb-0">時間</label>
                                <input type="time" id="schedule_time" name="schedule_time" class="form-control"
                                    style="width: 130px;">
                            </div>

                            <!-- 三顆按鈕 -->
                            <div class="d-flex flex-wrap align-items-center ml-4" style="gap: 0.5rem;">
                                <button type="submit" name="action" value="schedule" class="btn btn-dark">
                                    排程發布
                                </button>
                                <button type="submit" name="action" value="publish" class="btn btn-success">
                                    立即發布
                                </button>
                                <button type="submit" name="action" value="draft" class="btn btn-warning text-white">
                                    暫不發布（草稿）
                                </button>
                            </div>

                        </div>

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
      height: 500,
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
      filebrowserUploadUrl: '<?= BASE_URL ?>/?page=article_image_upload',
      filebrowserImageUploadUrl: '<?= BASE_URL ?>/?page=article_image_upload', // filebrowserImageUploadUrl這個key用在圖片上傳
      filebrowserUploadMethod: 'form',

      // 圖說設定
      image2_alignClasses: ['image-align-left', 'image-align-center', 'image-align-right'],
      image2_captionedClass: 'image-captioned',
      image_prefillDimensions: false,
      image2_disableResizer: false
    });

    // 可選：確認載入成功
    editor.on('instanceReady', function() {
      console.log('CKEditor loaded without visible warning.');
    });
  }

});

</script>

<style>
/* @media (max-width: 768px) {
    .form-group.d-flex {
        flex-direction: column;
        align-items: stretch;
    }

    .form-group.d-flex .d-flex.flex-wrap {
        justify-content: space-between;
    }

    .form-group.d-flex .btn {
        width: 100%;
    }
} */

</style>