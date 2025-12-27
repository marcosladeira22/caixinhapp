<h2><?= htmlspecialchars($title) ?></h2>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Usuário</th>
            <th>Perfil</th>
            <th>Ação</th>
            <th>Controller</th>
            <th>Método</th>
            <th>IP</th>
            <th>Data</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($logs)): ?>
            <tr>
                <td colspan="8">Nenhum log encontrado.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?= $log['id'] ?></td>
                    <td><?= $log['user_id'] ?></td>
                    <td><?= htmlspecialchars($log['role']) ?></td>
                    <td><?= htmlspecialchars($log['action']) ?></td>
                    <td><?= htmlspecialchars($log['controller']) ?></td>
                    <td><?= htmlspecialchars($log['method']) ?></td>
                    <td><?= htmlspecialchars($log['ip_address']) ?></td>
                    <td><?= date('d/m/Y H:i:s', strtotime($log['created_at'])) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
