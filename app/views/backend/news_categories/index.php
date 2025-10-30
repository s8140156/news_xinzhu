<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 mb-4">
            <form method="POST" action="<?= BASE_URL ?>/index.php?page=category_store" id="categoryForm">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">新聞分類管理</h6>
                    </div>

                    <div class="card-body">
                        <label class="form-label mb-3">（可拖曳變更順序）</label>
                        <button type="button" class="btn btn-primary" id="addRowBtn">+ 新增一筆</button>
                        <input type="hidden" name="act" value="addCategory">
                        <input type="hidden" name="delete_ids[]" value="">
                        <table class="table table-bordered" id="categoryTable">
                            <thead class="table-active">
                                <tr>
                                    <th>順序</th>
                                    <th>分類名稱</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody id="categoryTableBody">
                                <?php foreach($categories as $row): ?>
                                <tr class="<?= $row['is_focus'] === 1 ? 'fixed-row' : '' ?>">
                                    <td><input type="text" class="form-control bg-light" name="sort[]"
                                            value="<?= htmlspecialchars($row['sort']) ?>" readonly></td>
                                    <td><input type="text" class="form-control" name="name[]"
                                            value="<?= htmlspecialchars($row['name']) ?>"></td>
                                    <td>
                                        <input type="hidden" name="id[]" value="<?= htmlspecialchars($row['id']) ?>">
                                        <?php if($row['is_focus'] == 1): ?>
                                        <button type="button" class="btn btn-secondary" disabled>固定</button>
                                        <?php else: ?>
                                        <button type="button" class="btn btn-danger deleteRowBtn">刪除</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <button type="submit" class="btn btn-info">儲存</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// 新增列
document.getElementById('addRowBtn').addEventListener('click', function() {
    const tbody = document.getElementById('categoryTableBody');
    const rowCount = tbody.querySelectorAll('tr').length + 1;
    const newRow = document.createElement('tr');

    newRow.innerHTML = `
        <td><input type="text" class="form-control bg-light" name="id[]" value="(新)" readonly></td>
        <td><input type="text" class="form-control" name="name[]" value=""></td>
        <td><button type="button" class="btn btn-danger deleteRowBtn">刪除</button></td>
    `;
    tbody.appendChild(newRow);
});

// 刪除列
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('deleteRowBtn')) {
        const row = e.target.closest('tr');
        const idInput = row.querySelector('input[name="id[]"]');
        const form = document.getElementById('categoryForm');

        if (idInput && idInput.value && idInput.value !== '(新)') {
            // 既有資料 標記刪除
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'delete_ids[]';
            hidden.value = idInput.value;
            form.appendChild(hidden);
        }
        row.remove();
    }
});

let isDirty = false;

// 當輸入框有改變
$(document).on('input', 'input[name="name[]"], input[name="sort[]"]', function() {
  isDirty = true;
});

// 當有拖曳動作
$("#categoryTableBody").on("sortupdate", function() {
  isDirty = true;
});

// 當使用者要關閉或重整頁面時提醒
window.addEventListener('beforeunload', function (e) {
  if (isDirty) {
    e.preventDefault();
    e.returnValue = "您尚未儲存變更，確定要離開嗎？";
  }
});

// 當送出表單（儲存）時，重置旗標
document.getElementById('categoryForm').addEventListener('submit', function() {
  isDirty = false;
});


$(function() {
    // 拖曳排序
    $("#categoryTableBody").sortable({
        axis: "y",
        cursor: "move",
        handle: "td",
        placeholder: "sortable-placeholder",
        items: "tr:not(.fixed-row)", // 固定列不可拖曳(只允許非 fixed-row 的列可拖曳)
        update: function(event, ui) {
            $("#categoryTableBody tr").each(function(index) {
                console.log("順序已更新");
                $(this).find('input[name="sort[]"]').val(index + 1);
            });
        }
    });
});

document.getElementById('categoryForm').addEventListener('submit', function() {
    const sortInputs = document.querySelectorAll('input[name="sort[]"]');
    sortInputs.forEach((input, index) => {
        input.value = index + 1;
    });
});
</script>

<style>
/* 拖曳佔位樣式 */
.sortable-placeholder {
    background-color: #f0f0f0;
    height: 50px;
    border: 2px dashed #aaa;
}
</style>