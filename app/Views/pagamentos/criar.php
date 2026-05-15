<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold m-0">Lançar pagamento</h4>
        <?php require __DIR__ . '/../partials/voltar.php'; ?>
    </div>

    <?php require __DIR__ . '/../components/alert.php'; ?>

    <div class="card shadow">
        <div class="card-body">

            <form method="post" id="formPagamento">

                <!-- CSRF -->
                <input type="hidden" name="_token" value="<?= \Core\Csrf::gerarToken() ?>">

                <div class="mb-3">
                    <label class="form-label fw-semibold">Mês de referência</label>
                    <input type="month" name="mes_referencia" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Data do pagamento</label>
                    <input type="date" name="data_pagamento" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Valor pago</label>
                    <input type="number" step="0.01" name="valor" class="form-control" required>

                    <small class="text-muted">
                        Quantidade de cotas: <?= isset($quantidade_cotas) ? (int)$quantidade_cotas : 0 ?>
                    </small>
                </div>

                <!-- BOTÕES -->
                <div class="d-flex gap-2">

                    <button 
                        class="btn btn-success flex-grow-1 d-flex justify-content-center align-items-center"
                        id="btnSubmit"
                    >
                        Registrar pagamento
                    </button>

                    <!-- ✅ CORREÇÃO IMPORTANTE AQUI -->
                    <a 
                        href="<?= base_url("?rota=pagamento@index&grupo_id={$grupo_id}") ?>" 
                        class="btn btn-outline-secondary"
                    >
                        Cancelar
                    </a>

                </div>

            </form>

        </div>
    </div>

</div>

<script>
document.getElementById('formPagamento').addEventListener('submit', function () {
    const btn = document.getElementById('btnSubmit');
    btn.disabled = true;
    btn.innerText = 'Registrando...';
});
</script>