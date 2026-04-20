<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <title>Dashboard Admin</title>
</head>
<body>
    <div class="container mt-4">
        <h2>Dashboard do Administrador</h2>

        <p>
            Você está gerenciando o grupo ID <strong><?= htmlspecialchars($grupo_id) ?></strong>
        </p>

        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card p-3 shadow">
                    <strong>Total em Caixa</strong>
                    <p>R$ 0,00</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-3 shadow">
                    <strong>Usuários</strong>
                    <p>0</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-3 shadow">
                    <strong>Empréstimos</strong>
                    <p>0</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-3 shadow">
                    <strong>Inadimplentes</strong>
                    <p>0</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>