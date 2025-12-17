<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">

    <!-- Exibe o título enviado pelo controller -->
    <title><?= $title ?></title>

    <!-- CSS público -->
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

    <!-- Conteúdo principal -->
    <h1><?= $title ?></h1>

    <ul>
    <?php foreach ($users as $user): ?>
        <li>
            <?= $user['name']; ?> - <?= $user['email']; ?>
        </li>
    <?php endforeach; ?>
</ul>

</body>
</html>
