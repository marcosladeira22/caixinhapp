<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold m-0">Relatório financeiro</h4>

        <a href="<?= base_url('?rota=dashboard@grupo&grupo_id=' . (int)$grupo_id) ?>" 
           class="btn btn-outline-secondary">
            ← Voltar
        </a>
    </div>

    <?php require __DIR__ . '/../components/alert.php'; ?>

    <?php if (!empty($dados)): ?>

        <div class="row g-3">

            <div class="col-md-3">
                <div class="card shadow h-100 text-center">
                    <div class="card-body">
                        <h6 class="text-muted">Total arrecadado</h6>
                        <h5 class="fw-bold text-success">
                            R$ <?= number_format($dados['total_pagamentos'] ?? 0, 2, ',', '.') ?>
                        </h5>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow h-100 text-center">
                    <div class="card-body">
                        <h6 class="text-muted">Total emprestado</h6>
                        <h5 class="fw-bold text-primary">
                            R$ <?= number_format($dados['total_emprestado'] ?? 0, 2, ',', '.') ?>
                        </h5>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow h-100 text-center">
                    <div class="card-body">
                        <h6 class="text-muted">Taxas e juros</h6>
                        <h5 class="fw-bold text-warning">
                            R$ <?= number_format($dados['total_taxas'] ?? 0, 2, ',', '.') ?>
                        </h5>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow h-100 text-center">
                    <div class="card-body">
                        <h6 class="text-muted">Saldo atual</h6>
                        <h5 class="fw-bold text-dark">
                            R$ <?= number_format($dados['saldo_atual'] ?? 0, 2, ',', '.') ?>
                        </h5>
                    </div>
                </div>
            </div>

        </div>

        <div class="mt-4">

            <a href="<?= base_url('?rota=fechamento@resumo&grupo_id=' . (int)$grupo_id) ?>" 
               class="btn btn-danger">
                Fechar caixinha
            </a>

        </div>

    <?php else: ?>

        <div class="text-center p-5 bg-white shadow rounded">
            <h5 class="text-muted">Nenhum dado disponível</h5>
            <p class="text-muted mb-0">Não foi possível carregar o relatório financeiro.</p>
        </div>

    <?php endif; ?>

</div>
