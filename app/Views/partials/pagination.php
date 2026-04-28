<?php if ($paginator->totalPaginas > 1): ?>
<nav>
    <ul class="pagination justify-content-center">
        <?php for ($i = 1; $i <= $paginator->totalPaginas; $i++): ?>
            <li class="page-item <?= $i == $paginator->paginaAtual ? 'active' : '' ?>">
                <a href="?page=<?= $i ?>" class="page-link">
                    <?= $i ?>
                </a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>
<?php endif; ?>
