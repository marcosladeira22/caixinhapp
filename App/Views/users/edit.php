<h1><?= $title ?></h1>

<form method="post" action="<?= $base_url ?>/user/update/<?= $user['id']; ?>">
    <input type="text" name="name" value="<?= $user['name']; ?>" required>
    <input type="email" name="email" value="<?= $user['email']; ?>" required>

    <button type="submit">Atualizar</button>
</form>

