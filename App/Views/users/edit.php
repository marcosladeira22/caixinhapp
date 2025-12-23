<h1><?= $title ?></h1>

<form method="post" action="<?= $base_url ?>/user/update/<?= $user['id']; ?>" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= $this->csrfToken() ?>">
    <label for="">Nome</label>
    <input type="text" name="name" placeholder="Nome" required
        value="<?= htmlspecialchars($user['name']) ?>"
        <?= $_SESSION['user']['role'] === 'user' ? 'readonly' : '' ?>>
    <label for="">E-mail</label>
    <input type="email" name="email" placeholder="Email" required
        value="<?= htmlspecialchars($user['email']) ?>"
        <?= $_SESSION['user']['role'] === 'user' ? 'readonly' : '' ?>>
    <!-- Role (apenas admin/manager) -->
    <?php if (in_array($_SESSION['user']['role'], ['admin', 'manager'])): ?>
        <label>Perfil</label>
        <select name="role">
            <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>Usuário</option>
            <option value="manager" <?= $user['role'] === 'manager' ? 'selected' : '' ?>>Gerente</option>

            <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
            <?php endif; ?>
        </select>
    <?php endif; ?>
    <br><br>
    <label for="">Nova Senha</label>
    <input type="password" name="password" placeholder="Senha">
    <br>
    <small>(Deixe em branco para não alterar)</small>

    <br><br>
    <button type="submit">Atualizar</button>
</form>
