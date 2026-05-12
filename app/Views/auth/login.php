<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Login</title>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="col-md-4 offset-md-4">
            <div class="card shadow p-4">

                <h4 class="text-center mb-3">Login</h4>

                <?php if (!empty($erro)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
                <?php endif; ?>

                <form method="post">
                    <input type="hidden" name="_token" value="<?= \Core\Csrf::gerarToken() ?>">
                    <input type="email" name="email" class="form-control mb-3" placeholder="E-mail" required>
                    <input type="password" name="senha" class="form-control mb-3" placeholder="Senha" required>
                    <button class="btn btn-primary w-100">Entrar</button>
                </form>

                <div class="text-center mt-3">
                    <a href="<?php base_url('?rota=auth@cadastro') ?>">Quero criar minha caixinha</a>
                </div>

            </div>
        </div>
    </div>
</body>
</html>