<h3>Empréstimos</h3>

<table class="table table-striped">
    <thead>
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