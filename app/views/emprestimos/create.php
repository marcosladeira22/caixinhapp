<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <h4>💸 Novo Empréstimo</h4>

        <a href="<?= BASE_URL ?>/emprestimos?grupo_id=<?= $grupo_id ?>" 
           class="btn btn-outline-secondary mb-3">
            ← Voltar
        </a>
    </div>

    <hr>

    <!-- ALERTAS -->
    <?php if ($valorMaxPermitido <= 0): ?>

        <div class="alert alert-danger">
            ❌ Você está bloqueado por risco alto.
        </div>

    <?php else: ?>

        <div class="alert alert-info">
            <strong>Seu limite:</strong> 
            R$ <?= number_format($valorMaxPermitido, 2, ',', '.') ?>
            <br>
            <strong>Score:</strong> <?= number_format($score, 0) ?>
        </div>

    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/emprestimos/store">

        <input type="hidden" name="grupo_id" value="<?= $grupo_id ?>">

        <!-- USUÁRIO -->
        <div class="mb-3">
            <label class="form-label fw-bold">Usuário</label>

            <select name="usuario_id" class="form-select" required>
                <option value="">Selecione</option>

                <?php foreach($membros as $m): ?>
                    <option value="<?= $m['id'] ?>">
                        <?= $m['nome'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- VALOR -->
        <div class="row">

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Valor</label>

                <input 
                    type="number"
                    step="0.01"
                    name="valor"
                    id="valor"
                    class="form-control"
                    max="<?= $valorMaxPermitido ?>"
                    value="<?= $valorMaxPermitido > 0 ? $valorMaxPermitido : '' ?>"
                    required
                >
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">
                    Valor com juros (estimado)
                </label>

                <input type="text" id="preview" class="form-control" disabled>
            </div>

        </div>

        <!-- DATAS -->
        <div class="row">

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Data empréstimo</label>
                <input type="date" name="data_emprestimo" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Vencimento</label>
                <input type="date" name="data_vencimento" class="form-control" required>
            </div>

        </div>

        <button 
            id="btnSubmit"
            class="btn btn-success mt-3"
            <?= $valorMaxPermitido <= 0 ? 'disabled' : '' ?>
        >
            Solicitar Empréstimo
        </button>

    </form>

</div>

<script>

const input = document.getElementById('valor');
const preview = document.getElementById('preview');
const btn = document.getElementById('btnSubmit');

input.addEventListener('input', function () {

    let valor = parseFloat(this.value) || 0;
    let max = parseFloat(this.max);

    // 🚫 bloqueio se ultrapassar limite
    if (valor > max) {
        btn.disabled = true;
        input.classList.add('is-invalid');
    } else {
        btn.disabled = false;
        input.classList.remove('is-invalid');
    }

    // 💰 cálculo juros
    let tipo = "<?= $regra['juros_inicial_tipo'] ?? 'percentual' ?>";
    let taxa = parseFloat("<?= $regra['juros_inicial_valor'] ?? 0 ?>");

    let total = tipo === 'percentual'
        ? valor + (valor * (taxa / 100))
        : valor + taxa;

    preview.value = total.toLocaleString('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    });

});

</script>