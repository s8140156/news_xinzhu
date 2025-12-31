<?php
if (($pager['totalPages'] ?? 1) <= 1) {
    return;
}

$page = $pager['page'];
$totalPages = $pager['totalPages'];
?>

<nav class="backend-pagination mt-4">
    <ul class="pagination justify-content-center">

        <!-- 上一頁 -->
        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
            <a class="page-link"
                href="?<?= http_build_query(array_merge($_GET, ['p' => $page - 1])) ?>">
                上一頁
            </a>
        </li>

        <?php
        // 顯示頁碼範圍（例如只顯示前後 2 頁）
        $start = max(1, $page - 2);
        $end   = min($totalPages, $page + 2);

        for ($i = $start; $i <= $end; $i++):
        ?>
            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                <a class="page-link"
                    href="?<?= http_build_query(array_merge($_GET, ['p' => $i])) ?>">
                    <?= $i ?>
                </a>
            </li>
        <?php endfor; ?>

        <!-- 下一頁 -->
        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
            <a class="page-link"
                href="?<?= http_build_query(array_merge($_GET, ['p' => $page + 1])) ?>">
                下一頁
            </a>
        </li>

    </ul>
</nav>

<style>
    /* .backend-pagination .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
    } */

    .backend-pagination .page-link {
        color: #333;
    }

    .backend-pagination .page-item.disabled .page-link {
        color: #bbb;
    }
</style>