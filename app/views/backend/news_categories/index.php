<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 mb-4">
            <form id="categoryForm">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">新聞分類管理</h6>
                    </div>

                    <div class="card-body">
                        <label class="form-label mb-3">（可拖曳變更順序）</label>
                        <button type="button" class="btn btn-primary" id="addRowBtn">+ 新增一筆</button>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-active">
                                    <tr>
                                        <th style="width: 100px;">順序</th>
                                        <th>分類名稱</th>
                                        <th style="width: 150px;">操作</th>
                                    </tr>
                                </thead>
                                <tbody id="categoryTableBody">
                                    <?php foreach($categories as $row): ?>
                                    <tr>
                                        <td><input type="text" class="form-control bg-light" value="<?=$row['id']?>"
                                                readonly></td>
                                        <td><input type="text" class="form-control" value="<?=$row['name']?>"></td>
                                        <td>
                                            <?php if($row['is_focus']==1): ?>
                                            <button type="button" class="btn btn-danger" disabled>固定</button>
                                            <?php else: ?>
                                            <button type="button" class="btn btn-danger deleteRowBtn">刪除</button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <!-- <tr>
                                        <td><input type="text" class="form-control bg-light" value="2" readonly></td>
                                        <td><input type="text" class="form-control" value="國際新聞"></td>
                                        <td><button type="button" class="btn btn-danger deleteRowBtn">刪除</button></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" class="form-control bg-light" value="3" readonly></td>
                                        <td><input type="text" class="form-control" value="財經新聞"></td>
                                        <td><button type="button" class="btn btn-danger deleteRowBtn">刪除</button></td>
                                    </tr> -->
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="btn btn-info mt-2">儲存</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 拖曳功能 + 動態行 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script>
$(function() {
    // 拖曳排序
    $("#categoryTableBody").sortable({
        axis: "y",
        cursor: "move",
        handle: "td",
        placeholder: "sortable-placeholder",
        update: function(event, ui) {
            console.log("順序已更新");
        }
    });

    // 新增列
    $("#addRowBtn").on("click", function() {
        const tableBody = $("#categoryTableBody");
        const currentCount = tableBody.find("tr").length + 1;

        const newRow = `
      <tr>
        <td><input type="text" class="form-control bg-light" value="${currentCount}" readonly></td>
        <td><input type="text" class="form-control" placeholder="請輸入分類名稱"></td>
        <td><button type="button" class="btn btn-danger deleteRowBtn">刪除</button></td>
      </tr>
    `;
        tableBody.append(newRow);
    });

    // 刪除列
    $(document).on("click", ".deleteRowBtn", function() {
        if (confirm("確定要刪除此分類嗎？")) {
            $(this).closest("tr").remove();
        }
    });

    // 儲存表單（暫不送出，只預留動作）
    $("#categoryForm").on("submit", function(e) {
        e.preventDefault();
        alert("已點擊儲存（這裡之後可串接 PHP）");
    });
});

// 拖曳佔位樣式
</script>
<style>
.sortable-placeholder {
    background-color: #f0f0f0;
    height: 50px;
    border: 2px dashed #aaa;
}
</style>