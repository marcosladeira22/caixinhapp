<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <title>Dashboard Membro</title>
</head>
<body>

<div class="container mt-4">
    <h2>Meu Dashboard</h2>

    <p>
        Você participa do grupo ID <strong><?= htmlspecialchars($grupo_id) ?></strong>
    </p>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card p-3 shadow">
                <strong>Minhas Cotas</strong>
                <p>1</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3 shadow">
                <strong>Meu Score</strong>
                <p>100</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3 shadow">
                <strong>Empréstimos</strong>
                <p>Nenhum</p>
            </div>
        </div>
    </div>
</div>

</body>
</html>