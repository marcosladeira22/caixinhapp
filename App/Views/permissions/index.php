<h1><?= $title ?></h1>

<?php foreach ($roles as $role): ?>
    <h3><?= ucfirst($role) ?></h3>

    <form method="POST" action="<?= $base_url ?>/permission/update">
        <input type="hidden" name="csrf_token" value="<?= $this->csrfToken() ?>">
        <input type="hidden" name="role" value="<?= $role ?>">

        <?php foreach ($permissions as $permission): ?>
            <label>
                <input type="checkbox" name="permissions[]"value="<?= $permission['id'] ?>"
                    <?= in_array($permission['id'], $rolePermissions[$role]) ? 'checked' : '' ?>>
                <?= $permission['description'] ?>
            </label>
            <br>
        <?php endforeach; ?>

        <button type="submit">Salvar</button>
    </form>

    <hr>
<?php endforeach; ?>
