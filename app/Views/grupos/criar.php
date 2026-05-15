<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold m-0">Criar nova caixinha</h4>

        <a href="<?= base_url('?rota=dashboard@index') ?>" class="btn btn-outline-secondary">
            Voltar
        </a>
    </div>

    <?php require __DIR__ . '/../components/alert.php'; ?>

    <div class="card shadow">
        <div class="card-body">

            <form method="post" id="formGrupo">

                <!-- CSRF -->
                <input type="hidden" name="_token" value="<?= \Core\Csrf::gerarToken() ?>">

                <div class="row">

                    <!-- Nome -->
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-semibold">Nome do grupo</label>
                        <input type="text" name="nome" class="form-control" required>
                    </div>

                    <!-- Valor da cota -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Valor da cota (R$)</label>
                        <input type="number" step="0.01" name="valor_cota" class="form-control" required>
                    </div>

                    <!-- Dias de tolerância -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Dias de tolerância</label>
                        <input type="number" name="dias_tolerancia" class="form-control" value="0">
                    </div>

                    <!-- Empréstimo mínimo -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Empréstimo mínimo (R$)</label>
                        <input type="number" step="0.01" name="emprestimo_min" class="form-control">
                    </div>

                    <!-- Empréstimo máximo -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Empréstimo máximo (R$)</label>
                        <input type="number" step="0.01" name="emprestimo_max" class="form-control">
                    </div>

                    <!-- Tipo de taxa -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Tipo de taxa</label>
                        <select name="taxa_tipo" class="form-select">
                            <option value="fixo">Valor fixo</option>
                            <option value="percentual">Percentual</option>
                        </select>
                    </div>

                    <!-- Valor da taxa -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Valor da taxa</label>
                        <input type="number" step="0.01" name="taxa_valor" class="form-control" required>
                    </div>

                    <!-- Tipo de juros -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Tipo de juros</label>
                        <select name="juros_tipo" class="form-select">
                            <option value="fixo">Valor fixo</option>
                            <option value="percentual">Percentual</option>
                        </select>
                    </div>

                    <!-- Valor dos juros -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold">Valor dos juros</label>
                        <input type="number" step="0.01" name="juros_valor" class="form-control" required>
                    </div>

                </div>

                <!-- Botões -->
                <div class="d-flex gap-2">

                    <button 
                        type="submit"
                        class="btn btn-success flex-grow-1 d-flex justify-content-center align-items-center"
                        id="btnSubmit"
                    >
                        Criar grupo
                    </button>

                    <a href="<?= base_url('?rota=dashboard@index') ?>" class="btn btn-outline-secondary">
                        Cancelar
                    </a>

                </div>

            </form>

        </div>
    </div>

</div>

<!-- Prevenção de duplo envio -->
<script>
document.getElementById('formGrupo').addEventListener('submit', function () {
    const btn = document.getElementById('btnSubmit');
    btn.disabled = true;
    btn.innerText = 'Criando grupo...';
});
</script>