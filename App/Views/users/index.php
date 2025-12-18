<h1><?= $title ?></h1>

<ul>
    <?php foreach ($users as $user): ?>
        <li>
            <?= $user['name']; ?> (<?= $user['email']; ?>)

            <a href="<?= $base_url ?>/user/edit/<?= $user['id']; ?>">Editar</a>
            <a href="<?= $base_url ?>/user/delete/<?= $user['id']; ?>" onclick="return confirm('Excluir usuário?')">
               Excluir
            </a>
        </li>
    <?php endforeach; ?>
</ul>
