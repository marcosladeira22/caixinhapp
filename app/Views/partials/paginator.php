<?php
/**
 * Esperado:
 * - $paginator (Core\Paginator)
 * - $rota (string ex: 'pagamento@index')
 * - $grupo_id (int | null)
 * - $extras (array opcional: filtros adicionais)
 */

$extras = $extras ?? [];
?>

<?php if ($paginator->temResultados()): ?>

<div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">

    <!-- Seletor de itens por página -->
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
        <select name="per_page" class="form-select form-select-sm w-auto"
                onchange="this.form.submit()">
            <?php foreach ([5, 10, 20, 50] as $n): ?>
                <option value="<?= $n ?>" <?= $paginator->porPagina == $n ? 'selected' : '' ?>>
                    <?= $n ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <!-- Paginação -->
    <?php if ($paginator->totalPaginas > 1): ?>
    <nav>
        <ul class="pagination pagination-sm mb-0">
            <?php for ($i = 1; $i <= $paginator->totalPaginas; $i++): ?>
                <li class="page-item <?= $i == $paginator->paginaAtual ? 'active' : '' ?>">
                    <a class="page-link"
                       href="<?= base_url('?rota=' . $rota
                            . (!empty($grupo_id) ? '&grupo_id=' . (int)$grupo_id : '')
                            . '&page=' . $i
                            . (!empty($extras) ? '&' . http_build_query($extras) : '')
                       ) ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
    <?php endif; ?>

</div>

<?php else: ?>

<div class="alert alert-info mt-3">
    Nenhum resultado encontrado.
</div>

<?php endif; ?>