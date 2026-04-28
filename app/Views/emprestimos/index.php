
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
            <td>R$ <?= number_format($e['valor_solicitado'],2,',','.') ?></td>
            <td>R$ <?= number_format($e['valor_total'],2,',','.') ?></td>
            <td><?= $e['status'] ?></td>
            <td><?= $e['data_vencimento'] ?: '-' ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>