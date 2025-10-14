<!-- ========================================================= -->
<!-- Backend 共用 JavaScript 引用區塊                          -->
<!-- 建議放在 main.php 最底部（</body> 前）                     -->
<!-- ========================================================= -->

<!-- ✅ jQuery 一定要在最前面 -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- ✅ Bootstrap Bundle（含 Popper） -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- 📁 專案自訂共用 JS -->
<script src="/assets/backend/js/comm.js"></script>

<!-- 💫 jQuery Easing（動畫過渡效果） -->
<script src="/assets/backend/vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- 🧩 SB Admin 2 主控制腳本 -->
<script src="/assets/backend/js/sb-admin-2.min.js"></script>

<!-- 📊 Chart.js（如 dashboard 有圖表） -->
<script src="/assets/backend/vendor/chart.js/Chart.min.js"></script>

<!-- 📋 DataTables（表格插件） -->
<script src="/assets/backend/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/backend/vendor/datatables/dataTables.bootstrap4.min.js"></script>

<!-- 🔽 Select2（下拉選單增強插件） -->
<link
  href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
  rel="stylesheet"
/>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- 📄 頁面級自訂腳本 -->
<script>
$(document).ready(function() {
  // DataTables 初始化範例
  if ($('#dataTable').length) {
    $('#dataTable').DataTable();
  }

  // Select2 初始化範例
  if ($('.select2').length) {
    $('.select2').select2();
  }
});
</script>
