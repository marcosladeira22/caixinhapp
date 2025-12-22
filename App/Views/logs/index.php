<h2>Auditoria do Sistema</h2>

<form method="get">
    <input type="text" name="search"
           placeholder="Buscar usuário ou descrição"
           value="<?= htmlspecialchars($search); ?>">

    <select name="action">
        <option value="">Todas ações</option>
        <option value="delete_user">Delete</option>
        <option value="restore_user">Restore</option>
        <option value="login">Login</option>
    </select>

    <button type="submit">Filtrar</button>
</form>

<table border="1" cellpadding="5">
    <thead>
        <tr>
            <th>ID Log</th>
            <th>Usuário</th>
            <th>Ação</th>
            <th>Descrição</th>
            <th>Data</th>
        </tr>
    </thead>
    <tbody>

    <?php if (empty($logs)): ?>
        <tr>
            <td colspan="5">Nenhum log encontrado.</td>
        </tr>
    <?php else: ?>
        <?php foreach ($logs as $log): ?>
            <tr>
                <td><?= $log['id']; ?></td>
                <td><?= htmlspecialchars($log['name'] ?? 'Sistema'); ?></td>
                <td><?= $log['action']; ?></td>
                <td><?= htmlspecialchars($log['description']); ?></td>
                <td><?= $log['created_at']; ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>

    </tbody>
</table>

<!-- Paginação -->
<?php for ($i = 1; $i <= $pages; $i++): ?>
    <a href="?page=<?= $i; ?>&search=<?= urlencode($search); ?>&action=<?= urlencode($action); ?>">
        <?= $i; ?>
    </a>
<?php endfor; ?>
