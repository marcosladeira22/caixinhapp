<h1><?= $title ?></h1>

<?php if ($error = $this->getFlash('error')): ?>
    <p style="color:red"><?= $error ?></p>
<?php endif; ?>

<form method="post" action="<?= $base_url ?>/user/store">
    <input type="hidden" name="csrf_token" value="<?= $this->csrfToken() ?>">
    <input type="text" name="name" placeholder="Nome" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Senha" required>
    
    <button type="submit">Salvar</button>
</form>


