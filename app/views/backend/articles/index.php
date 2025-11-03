<form method="GET" action="">
    <input type="hidden" name="page" value="article_delete">
    <label for="id">輸入文章 ID：</label>
    <input type="number" name="id" id="id" placeholder="例如：87" required>
    <button type="submit" class="btn btn-danger btn-sm"
        onclick="return confirm('確定要刪除此文章嗎？此動作無法復原！')">
        刪除
    </button>
</form>
