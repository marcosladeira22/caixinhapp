<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Pagamentos de Cotas</h3>
    <?php require __DIR__ . '/../partials/voltar.php'; ?>
</div>

<hr>

<form method="get" class="mb-3">
    <input type="hidden" name="rota" value="pagamento@index">
    <input type="hidden" name="grupo_id" value="<?= (int)$grupo_id ?>">
    <input type="hidden" name="page" value="1">

    <label class="me-2">Resultados por página:</label>
    <select name="per_page" onchange="this.form.submit()">
        <?php foreach ([5, 10, 20, 50] as $n): ?>
            <option value="<?= $n ?>" <?= $paginator->porPagina == $n ? 'selected' : '' ?>>
                <?= $n ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>



<table class="table table-bordered">
    <thead class="table-light text-center">
        <tr>
            <th>Usuário</th>
            <th>Cotas</th>
            <th>Status</th>
            <th>Ação</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pagamentos as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['nome']) ?></td>
                <td class="text-center"><?= (int)$p['quantidade_cotas'] ?></td>
                <td class="text-center">
                    <?php if ($p['pagamento_id']): ?>
                        <span class="badge bg-success">Pago</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Em atraso</span>
                    <?php endif; ?>
                </td>
                <td class="text-center">
                    <?php if (!$p['pagamento_id']): ?>
                        <form method="post" action="<?= base_url('?rota=pagamento@pagar') ?>">
                            <input type="hidden" name="grupo_id" value="<?= (int)$grupo_id ?>">
                            <input type="hidden" name="usuario_id" value="<?= isset($p['usuario_id']) ? (int)$p['usuario_id'] : 0 ?>">
                            <button type="submit" class="btn btn-sm btn-success">
                                Marcar como Pago
                            </button>
                        </form>
                    <?php else: ?>
                        <span class="text-muted">—</span>
                    <?php endif; ?>
                </td>

            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php require __DIR__.'/../partials/pagination.php'; ?>

