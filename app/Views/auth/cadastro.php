<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Criar Conta</title>
</head>
<body class="bg-light">

    <div class="container mt-5">
        <div class="col-md-4 offset-md-4">
            <div class="card p-4 shadow">

                <h4 class="text-center">Criar minha caixinha</h4>

                <?php if (!empty($erro)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
                <?php endif; ?>

                <form method="post">
                    <input type="hidden" name="_token" value="<?= $csrfToken ?>">
                    <div class="mb-2">
                        <label for="" class="form-label">Nome</label>
                        <input type="text" name="nome" class="form-control" placeholder="Nome completo" required>
                    </div>
                    <div class="mb-2">
                        <label for="" class="form-label">E-mail</label>
                        <input type="email" name="email" class="form-control" placeholder="E-mail" required>
                    </div>
                    <div class="mb-2">
                        <label for="" class="form-label">Senha</label>
                        <input type="password" name="senha" class="form-control" placeholder="Senha" required>
                    </div>
                    <div class="mb-2">
                        <label for="" class="form-label">Telefone</label>
                        <input type="text" name="telefone" class="form-control" placeholder="Telefone (opcional)">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Sexo</label>
                        <select name="sexo" class="form-select">
                            <option value="O">Prefiro não informar</option>
                            <option value="M">Masculino</option>
                            <option value="F">Feminino</option>
                        </select>
                    </div>
                    <button class="btn btn-success w-100">Criar Conta</button>
                </form>
                <div class="text-center mt-3">
                    <a href="<?php base_url('?rota=auth@login') ?>">Já tenho conta</a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>