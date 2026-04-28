<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Logs do Sistema</h3>
    <?php require __DIR__ . '/../partials/voltar.php'; ?>
</div>

<hr>

<!-- Filtro por ação -->
<form method="get" class="mb-3">
    <!-- Mantém contexto da rota -->
    <input type="hidden" name="rota" value="log@index">
    <input type="hidden" name="grupo_id" value="<?= (int)$grupo_id ?>">
    <input type="hidden" name="page" value="1">

    <label class="me-2">Filtrar por ação:</label>
    <select name="acao" class="form-select w-25 d-inline">
        <option value="">Todas as ações</option>
        <option value="PAGAMENTO" <?= ($acao ?? '') === 'PAGAMENTO' ? 'selected' : '' ?>>Pagamento</option>
        <option value="EMPRESTIMO" <?= ($acao ?? '') === 'EMPRESTIMO' ? 'selected' : '' ?>>Empréstimo</option>
        <option value="USUARIO_GRUPO" <?= ($acao ?? '') === 'USUARIO_GRUPO' ? 'selected' : '' ?>>Usuário no Grupo</option>
        <option value="PERFIL" <?= ($acao ?? '') === 'PERFIL' ? 'selected' : '' ?>>Perfil</option>
        <option value="FECHAMENTO" <?= ($acao ?? '') === 'FECHAMENTO' ? 'selected' : '' ?>>Fechamento</option>
    </select>

    <button class="btn btn-secondary ms-2">Filtrar</button>
</form>
<table class="table table-striped">
    <thead class="table-light text-center">
        <tr>
            <th>Data</th>
            <th>Usuário</th>
            <th>Ação</th>
            <th>Descrição</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($logs as $log): ?>
            <tr>
                <td><?= date('d/m/Y H:i', strtotime($log['criado_em'])) ?></td>
                <td><?= htmlspecialchars($log['nome_usuario'] ?? 'Sistema') ?></td>
                <td><?= htmlspecialchars($log['acao']) ?></td>
                <td><?= htmlspecialchars($log['descricao']) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
$rota = 'log@index';
$extras = [
    'acao' => $acao
];
require __DIR__ . '/../partials/paginator.php';
?>