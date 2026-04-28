<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Pagamentos de Cotas</h3>
    <?php require __DIR__ . '/../partials/voltar.php'; ?>
</div>
<hr>
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
                <td><?= $p['quantidade_cotas'] ?></td>
                <td>
                    <?= $p['pagamento_id']
                        ? '<span class="badge bg-success">Pago</span>'
                        : '<span class="badge bg-danger">Em atraso</span>' ?>
                </td>                
                <td>
                    <?php if (!$p['pagamento_id']): ?>
                        <form method="post" action="<?= base_url('?rota=pagamento@pagar') ?>">
                            <input type="hidden" name="grupo_id" value="<?= $grupo_id ?>">
                            <input type="hidden" name="usuario_id" value="<?= $p['usuario_id'] ?>">
                            <button class="btn btn-sm btn-success">
                                Marcar como Pago
                            </button>
                        </form>
                    <?php else: ?>
                        <span class="text-muted">Pago</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>