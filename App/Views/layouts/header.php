<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'CaixinhApp' ?></title>

    <!-- CSS global -->
    <link rel="stylesheet" href="<?= $base_url ?>/css/style.css">
</head>
<body>

<header>
    <h2>CaixinhApp</h2>

    <nav>
        <a href="<?= $base_url ?>/user/index">Usuários</a>
        <a href="<?= $base_url ?>/user/create">Novo usuário</a>
    </nav>

    <hr>
</header>
