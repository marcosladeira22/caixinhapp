<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?? 'Sistema' ?></title>
    <!-- Bootstrap (carregado UMA VEZ só) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- HEADER -->
<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <span class="navbar-brand">💰 CaixinhApp</span>
        
        <?php if(isset($_SESSION['usuario_id'])): ?>
            <span class="navbar-brand"><?= $_SESSION['usuario_nome'] ?></span>
            <a href="<?= BASE_URL ?>/logout" class="btn btn-danger btn-sm">Sair</a>
        <?php endif; ?>
    </div>
</nav>

<!-- NAVBAR GLOBAL -->
 <nav class="navbar nav" ></nav>

<!-- CONTEÚDO -->
<div class="container mt-4">

    <!-- ALERTAS -->
    <?php if(isset($_SESSION['erro'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['erro']; unset($_SESSION['erro']); ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['sucesso'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?>
        </div>
    <?php endif; ?>

    <!-- AQUI VAI O CONTEÚDO DAS PÁGINAS -->
    <?php require_once $view; ?>

</div>

</body>
</html>