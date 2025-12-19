<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
</head>
<body>

<h2>Login</h2>

<?php if ($error = $this->getFlash('error')): ?>
    <p style="color:red"><?= $error ?></p>
<?php endif; ?>

<?php if ($success = $this->getFlash('success')): ?>
    <p style="color:green"><?= $success ?></p>
<?php endif; ?>

<form method="POST" action="/caixinhapp/public/auth/login">
    <input type="hidden" name="csrf_token" value="<?= $this->csrfToken() ?>">
    
    <label>Email</label><br>
    <input type="email" name="email" required><br><br>

    <label>Senha</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Entrar</button>
</form>

</body>
</html>
