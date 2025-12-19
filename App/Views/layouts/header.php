<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <!-- CSS GLOBAL DO SISTEMA -->
    <link rel="stylesheet" href="<?= $base_url ?>/css/style.css">
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
        <a href="<?= $base_url ?>/home">Home</a>&nbsp;
        <?php if ($this->hasRole(['admin', 'manager'])): ?>|&nbsp;
            <a href="<?= $base_url ?>/user/index">Usuários</a>&nbsp;|&nbsp;
            <a href="<?= $base_url ?>/user/create">Novo usuário</a>
        <?php endif; ?>
    </nav>
<?php endif; ?>
<hr>
