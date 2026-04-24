<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Pagamentos de Cotas</h3>
    <?php require __DIR__ . '/../partials/voltar.php'; ?>
</div>
<hr>
<table class="table table-bordered">
    <thead>
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
                <td><?= $p['quantidade_cotas'] ?></td>
                <td>
                    <?= $p['pagamento_id']
                        ? '<span class="badge bg-success">Pago</span>'
                        : '<span class="badge bg-danger">Em atraso</span>' ?>
                </td>
                <td>
                    <?php if (!$p['pagamento_id']): ?>
                        <!-- aqui depois entra o botão de registrar pagamento -->
                        Pendente
                    <?php else: ?>
                        —
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>