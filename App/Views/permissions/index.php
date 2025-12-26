<h1><?= $title ?></h1>

<form method="POST" action="<?= $base_url ?>/permission/update">

    <input type="hidden" name="csrf_token" value="<?= $this->csrfToken() ?>">

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Permissão</th>
                <?php foreach ($roles as $role): ?>
                    <th><?= ucfirst($role) ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($permissions as $permission): ?>
                <tr>
                    <!-- Descrição da permissão -->
                    <td><?= $permission['description'] ?></td>

                    <!-- Checkbox por role -->
                    <?php foreach ($roles as $role): ?>
                        <td style="text-align:center">
                            <input
                                type="checkbox"
                                name="permissions[<?= $role ?>][]"
                                value="<?= $permission['name'] ?>"
                                <?= in_array($permission['name'], $rolePermissions[$role] ?? []) ? 'checked' : '' ?>
                            >
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <br><br>
    <button type="submit">Salvar permissões</button>
</form>
