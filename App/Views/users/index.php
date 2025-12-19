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
    <br>
    <!-- PAGINAÇÃO -->
    <div class="pagination">

        <!-- Botão Anterior -->
        <?php if ($page > 1): ?>
            <a href="<?= $base_url ?>/user/index?page=<?= $page - 1 ?>">← Anterior</a>
        <?php endif; ?>

        <!-- Página atual -->
        <span>Página <?= $page ?> de <?= $totalPages ?></span>

        <!-- Botão Próxima -->
        <?php if ($page < $totalPages): ?>
            <a href="<?= $base_url ?>/user/index?page=<?= $page + 1 ?>">Próxima →</a>
        <?php endif; ?>

    </div>
</ul>
