<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= $titulo ?? 'CaixinhApp' ?></title>

    <!-- Bootstrap (carregado uma única vez) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Menu superior -->
    <?php require __DIR__ . '/../partials/menu.php'; ?>

    <!-- Conteúdo da página -->
    <main class="container mt-4">
        <?= $conteudo ?>
    </main>

</body>
</html>