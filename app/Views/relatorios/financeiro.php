<h3>Relatório Financeiro da Caixinha</h3>

<div class="row mt-4">

    <div class="col-md-3">
        <div class="card p-3 shadow">
            <strong>Total Arrecadado</strong>
            <p>R$ <?= number_format($dados['total_pagamentos'], 2, ',', '.') ?></p>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card p-3 shadow">
            <strong>Total Emprestado</strong>
            <p>R$ <?= number_format($dados['total_emprestado'], 2, ',', '.') ?></p>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card p-3 shadow">
            <strong>Taxas e Juros</strong>
            <p>R$ <?= number_format($dados['total_taxas'], 2, ',', '.') ?></p>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card p-3 shadow">
            <strong>Saldo Atual</strong>
            <p>
                R$ <?= number_format($dados['saldo_atual'], 2, ',', '.') ?>
            </p>
        </div>
    </div>

</div>

<div class="mt-4">
    <button class="btn btn-danger">
        Fechar Caixinha (em breve)
    </button>
</div>