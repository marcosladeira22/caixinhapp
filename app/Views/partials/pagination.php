<?php if ($paginator->totalPaginas > 1): ?>

<nav>
    <ul class="pagination justify-content-center">

        <?php for ($i = 1; $i <= $paginator->totalPaginas; $i++): ?>

            <?php
                // ✅ mantém parâmetros existentes
                $params = array_merge(
                    $_GET,
                    [
                        'page' => $i
                    ]
                );

                $url = '?' . http_build_query($params);
            ?>

            <li class="page-item <?= $i == $paginator->paginaAtual ? 'active' : '' ?>">

                <a href="<?= $url ?>" class="page-link">
                    <?= $i ?>
                </a>

            </li>

        <?php endfor; ?>

    </ul>
</nav>

<?php endif; ?>
