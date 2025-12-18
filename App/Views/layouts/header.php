<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Sistema' ?></title>
</head>
<body>

<?php if ($success = $this->getFlash('success')): ?>
    <p style="color:green"><?= $success ?></p>
<?php endif; ?>

<?php if ($error = $this->getFlash('error')): ?>
    <p style="color:red"><?= $error ?></p>
<?php endif; ?>

<?php if (isset($_SESSION['user'])): ?>
    <p>
        Olá, <?= $_SESSION['user']['name'] ?> |
        <a href="/caixinhapp/public/auth/logout">Sair</a>
    </p>
    <nav>
        <a href="<?= $base_url ?>/user/index">Usuários</a>
        <a href="<?= $base_url ?>/user/create">Novo usuário</a>
    </nav>
<?php endif; ?>
<hr>
