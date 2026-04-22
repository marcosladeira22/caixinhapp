<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Lançar Pagamento</title>
</head>
<body>
    <div class="container mt-5">
        <h3>Lançar Pagamento</h3>

        <form method="post">

            <div class="mb-3">
                <label>Mês de Referência</label>
                <input type="month" name="mes_referencia" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Data do Pagamento</label>
                <input type="date" name="data_pagamento" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Valor Pago</label>
                <input type="number" step="0.01" name="valor" class="form-control" required>
                <small>Quantidade de cotas: <?= $quantidade_cotas ?></small>
            </div>

            <button class="btn btn-success">Registrar</button>
            /?rota=dashboard@index&grupo_id=<?= $grupo_id ?>Voltar</a>
        </form>
    </div>
</body>
</html>