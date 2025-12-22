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


<?php if (isset($_SESSION['user'])): $avatar = $_SESSION['user']['avatar'] ?? 'default.png';?>
    <img src="<?= $base_url ?>/uploads/avatars/<?= $_SESSION['user']['avatar'] ?? 'default.png'; ?>" 
    width="40" style="border-radius:50%;">
    <p>
        Olá, <?= $_SESSION['user']['name'] ?> |
        <a href="/caixinhapp/public/auth/logout">Sair</a>
    </p>
    <nav>
        <a href="<?= $base_url ?>/home">Home</a>&nbsp;|
        <a href="<?= $base_url ?>/user/avatar">Perfil</a>&nbsp;
        <?php if ($this->hasRole(['admin', 'manager'])): ?>|&nbsp;
            <a href="<?= $base_url ?>/user/index">Usuários</a>&nbsp;|&nbsp;
            <a href="<?= $base_url ?>/user/create">Novo usuário</a>
        <?php endif; ?>
        <?php if ($this->hasRole(['admin'])): ?>
            <a href="<?= $base_url ?>/user/deleted">&nbsp;|&nbsp;Usuários desativados</a>
            <a href="<?= $base_url ?>/log">&nbsp;|&nbsp;Logs</a>
        <?php endif; ?>
    </nav>
<?php endif; ?>
<hr>
