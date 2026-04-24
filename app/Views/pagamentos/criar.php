<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Lançar Pagamento</h3>
    <?php require __DIR__ . '/../partials/voltar.php'; ?>
</div>
<hr>
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
    <div class="text-center mt-3">
        <a href="<?php base_url("?rota=dashboard@index&grupo_id={$grupo['id']}") ?>" class="btn btn-secondary btn-sm">Voltar</a>
    </div>
</form>
