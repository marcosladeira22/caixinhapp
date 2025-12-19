<h1><?= $title ?></h1>

<ul>
    <?php foreach ($users as $user): ?>
        <li>
            <?= $user['name']; ?> (<?= $user['email']; ?>)
            <?php if ($this->hasRole(['admin', 'manager'])): ?>
                <a href="<?= $base_url ?>/user/edit/<?= $user['id']; ?>">Editar</a>
            <?php endif; ?>
            <?php if ($this->hasRole(['admin'])): ?>
                <a href="<?= $base_url ?>/user/delete/<?= $user['id']; ?>"
                onclick="return confirm('Excluir usuário?')">Excluir
                </a>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>
