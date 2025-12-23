<h1><?= $title ?></h1>

<form method="get" action="<?= $base_url ?>/user/index">
    <input
        type="text"
        name="search"
        placeholder="Buscar por nome ou email"
        value="<?= htmlspecialchars($search ?? '') ?>"
    >
    <button type="submit">Buscar</button>
</form>
<br>
<th>
    <a href="<?= $base_url ?>/user/index?search=<?= urlencode($search) ?>&order=name&dir=<?= ($order === 'name' && $dir === 'asc') ? 'desc' : 'asc' ?>">
        Nome ↓&nbsp;
    </a>
</th>
<th>
    <a href="<?= $base_url ?>/user/index?search=<?= urlencode($search) ?>&order=email&dir=<?= ($order === 'email' && $dir === 'asc') ? 'desc' : 'asc' ?>">
        Email ↓
    </a>
</th>
<ul>
    <?php foreach ($users as $user): ?>
        <li>
            <?= $user['name']; ?> (<?= $user['email']; ?>)
            <?php if ($this->hasRole(['admin', 'manager'])): ?>
                <a href="<?= $base_url ?>/user/edit/<?= $user['id']; ?>">Editar</a>
                &nbsp;|&nbsp;
            <?php endif; ?>
            <?php if ($this->can('delete_user')): ?>
                <a href="<?= $base_url ?>/user/delete/<?= $user['id']; ?>"
                onclick="return confirm('Excluir usuário?')">Excluir
                </a>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
    <br>
    <?php if (empty($users)): ?>
        <div class="alert">
            <?php if (!empty($search)): ?>
                Nenhum resultado encontrado para
                <strong><?= htmlspecialchars($search) ?></strong>
            <?php else: ?>
                Nenhum usuário cadastrado.
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- PAGINAÇÃO -->
    <div class="pagination">

        <!-- Botão Anterior -->
        <?php if ($page > 1): ?>
            <a href="<?= $base_url ?>/user/index?page=<?= $page - 1 ?>&search=<?= urlencode($search ?? '') ?>&order=<?= $order ?>&dir=<?= $dir ?>">
                ← Anterior
            </a>
        <?php endif; ?>

        <!-- Página atual -->
        <span>Página <?= $page ?> de <?= $totalPages ?></span>

        <!-- Botão Próxima -->
        <?php if ($page < $totalPages): ?>
            <a href="<?= $base_url ?>/user/index?page=<?= $page + 1 ?>&search=<?= urlencode($search ?? '') ?>&order=<?= $order ?>&dir=<?= $dir ?>">
                Próxima →
            </a>
        <?php endif; ?>

    </div>
</ul>
