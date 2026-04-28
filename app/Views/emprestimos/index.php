
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Empréstimos</h3>
    <?php require __DIR__ . '/../partials/voltar.php'; ?>
</div>
<hr>
<form method="get" class="mb-3">
    <input type="hidden" name="rota" value="usuarioGrupo@index">
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
<table class="table table-striped">
    <thead class="table-light text-center">
        <tr>
            <th>Usuário</th>
            <th>Valor</th>
            <th>Total</th>
            <th>Status</th>
            <th>Vencimento</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($emprestimos as $e): ?>
        <tr>
            <td><?= htmlspecialchars($e['nome']) ?></td>
            <td class="text-center">R$ <?= number_format($e['valor_solicitado'], 2, ',', '.') ?></td>
            <td class="text-center"><?= htmlspecialchars($e['status']) ?></td>
            <td class="text-center">
                <?php if ($e['status'] === 'PENDENTE'): ?>
                    <a class="btn btn-sm btn-success"
                    href="<?= base_url('?rota=emprestimo@aprovar') . '&id=' . (int)$e['emprestimo_id'] ?>">
                        Aprovar
                    </a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php require __DIR__.'/../partials/pagination.php'; ?>
