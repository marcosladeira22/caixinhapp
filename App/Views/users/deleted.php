<h2>Usuários desativados</h2>

<?php if (empty($users)): ?>
    <p>Nenhum usuário desativado.</p>
<?php else: ?>

<table border="1" cellpadding="5">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Desativado em</th>
            <th>Ação</th>
        </tr>
    </thead>
    <tbody>

    <?php foreach ($users as $user): ?>
        <tr>
            <td><?= $user['id']; ?></td>
            <td><?= htmlspecialchars($user['name']); ?></td>
            <td><?= htmlspecialchars($user['email']); ?></td>
            <td><?= $user['deleted_at']; ?></td>
            <td>
                <a href="<?= $base_url ?>/user/restore/<?= $user['id']; ?>"
                   onclick="return confirm('Deseja restaurar este usuário?')">
                    Restaurar
                </a>
            </td>
        </tr>
    <?php endforeach; ?>

    </tbody>
</table>

<?php endif; ?>
