<?php
$extras = $extras ?? [];
?>

<?php if ($paginator->temResultados()): ?>

<div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">

    <!-- ✅ SELETOR DE ITENS -->
    <form method="get" class="d-flex align-items-center gap-2">

        <input type="hidden" name="rota" value="<?= htmlspecialchars($rota) ?>">

        <?php if (!empty($grupo_id)): ?>
            <input type="hidden" name="grupo_id" value="<?= (int)$grupo_id ?>">
        <?php endif; ?>

        <?php foreach ($extras as $chave => $valor): ?>
            <input type="hidden" name="<?= htmlspecialchars($chave) ?>" value="<?= htmlspecialchars($valor) ?>">
        <?php endforeach; ?>

        <input type="hidden" name="page" value="1">

        <label class="form-label mb-0">Resultados por página:</label>

        <select 
            name="per_page" 
            class="form-select form-select-sm w-auto"
            onchange="this.form.submit()"
        >
            <?php foreach ([5, 10, 20, 50] as $n): ?>
                <option value="<?= $n ?>" <?= $paginator->porPagina == $n ? 'selected' : '' ?>>
                    <?= $n ?>
                </option>
            <?php endforeach; ?>
        </select>

    </form>

    <!-- ✅ PAGINAÇÃO -->
    <?php if ($paginator->totalPaginas > 1): ?>

        <nav>
            <ul class="pagination pagination-sm mb-0">

                <?php for ($i = 1; $i <= $paginator->totalPaginas; $i++): ?>

                    <?php
                        // ✅ construir URL segura
                        $params = array_merge(
                            $_GET,
                            ['page' => $i]
                        );

                        $url = '?' . http_build_query($params);
                    ?>

                    <li class="page-item <?= $i == $paginator->paginaAtual ? 'active' : '' ?>">

                        <a 
                            class="page-link"
                            href="<?= $url ?>"
                        >
                            <?= $i ?>
                        </a>

                    </li>

                <?php endfor; ?>

            </ul>
        </nav>

    <?php endif; ?>

</div>

<?php else: ?>

<div class="text-center p-4 bg-white shadow rounded mt-3">
    <h6 class="text-muted mb-1">Nenhum resultado encontrado</h6>
    <small class="text-muted">Tente ajustar os filtros ou parâmetros da pesquisa.</small>
</div>

<?php endif; ?>