
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Empréstimos</h3>
    <?php require __DIR__ . '/../partials/voltar.php'; ?>
</div>
<hr>
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
<?php
$rota = 'emprestimo@index';
$extras = [];
require __DIR__ . '/../partials/paginator.php';
?>
