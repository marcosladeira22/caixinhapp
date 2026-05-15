<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($titulo ?? 'CaixinhApp') ?></title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<!-- ✅ NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">

        <a class="navbar-brand fw-bold" href="<?= base_url('?rota=dashboard@index') ?>">
            💰 CaixinhApp
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menu">

            <!-- ESQUERDA -->
            <ul class="navbar-nav me-auto">

                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('?rota=dashboard@index') ?>">
                        Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('?rota=grupo@index') ?>">
                        Grupos
                    </a>
                </li>

            </ul>

            <!-- DIREITA -->
            <ul class="navbar-nav">

                <li class="nav-item">
                    <span class="nav-link text-white">
                        <?= htmlspecialchars(\Core\Sessao::get('usuario_nome') ?? '') ?>
                    </span>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('?rota=usuario@perfil') ?>">
                        Perfil
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-danger" href="<?= base_url('?rota=auth@logout') ?>">
                        Sair
                    </a>
                </li>

            </ul>

        </div>
    </div>
</nav>

<!-- ✅ CONTEÚDO -->
<main class="mt-4">

    <!-- ALERT GLOBAL -->
    <div class="container mb-3">
        <?php require __DIR__ . '/../components/alert.php'; ?>
    </div>

    <!-- CONTEÚDO DINÂMICO -->
    <?= $conteudo ?>

</main>

<!-- Bootstrap JS (toggle menu mobile) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>