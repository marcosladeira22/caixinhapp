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
<!-- NAVBAR GLOBAL -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">

        <!-- Logo -->
        <a class="navbar-brand" href="<?= BASE_URL ?>/dashboard">
            💰 CaixinhApp
        </a>
        <!-- Botão mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menu">

            <!-- Menu esquerda -->
            <ul class="navbar-nav me-auto">

                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/dashboard">
                        Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/grupos/create">
                        Criar Grupo
                    </a>
                </li>

            </ul>

            <!-- Menu direita -->
            <ul class="navbar-nav">

                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/perfil">
                        👤 <?= $_SESSION['usuario_nome'] ?? 'Usuário' ?>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-danger" href="<?= BASE_URL ?>/logout">
                        Sair
                    </a>
                </li>

            </ul>

        </div>
    </div>
</nav>

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