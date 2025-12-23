<h1><?= $title ?></h1>

<?php if ($error = $this->getFlash('error')): ?>
    <p style="color:red"><?= $error ?></p>
<?php endif; ?>

<form method="post" action="<?= $base_url ?>/user/store">
    <input type="hidden" name="csrf_token" value="<?= $this->csrfToken() ?>">
    <label for="">Nome</label>
    <input type="text" name="name" placeholder="Nome" required>
    <label for="">E-mail</label>
    <input type="email" name="email" placeholder="Email" required>
    <label for="">Senha</label>
    <input type="password" name="password" placeholder="Senha" required>
    <!-- Role -->
    <label>Perfil</label>
    <select name="role">
        <option value="user">Usuário</option>
        <option value="manager">Gerente</option>
        <?php if ($_SESSION['user']['role'] === 'admin'): ?>
            <option value="admin">Administrador</option>
        <?php endif; ?>
    </select>
    <!-- Avatar 
    <label>Avatar</label>
    <input type="file" name="avatar" accept="image/*">
    -->
    <br><br>
    <button type="submit">Salvar</button>
</form>


