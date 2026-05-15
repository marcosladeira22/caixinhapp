<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold m-0">Solicitar empréstimo</h4>
        <?php require __DIR__ . '/../partials/voltar.php'; ?>
    </div>

    <?php require __DIR__ . '/../components/alert.php'; ?>

    <div class="card shadow">
        <div class="card-body">

            <form method="post" id="formEmprestimo">

                <!-- ✅ CSRF -->
                <input type="hidden" name="_token" value="<?= \Core\Csrf::gerarToken() ?>">

                <!-- VALOR -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Valor desejado</label>
                    <input 
                        type="number" 
                        step="0.01" 
                        name="valor" 
                        class="form-control"
                        placeholder="Ex: 1000.00"
                        required
                    >
                </div>

                <!-- PREVIEW -->
                <div class="alert alert-info mt-3" id="preview" style="display:none"></div>

                <!-- BOTÕES -->
                <div class="d-flex gap-2 mt-4">

                    <button 
                        class="btn btn-primary flex-grow-1 d-flex justify-content-center align-items-center"
                        id="btnSubmit"
                    >
                        Enviar solicitação
                    </button>

                    <!-- ✅ LINK CORRIGIDO -->
                    <a 
                        href="<?= base_url("?rota=dashboard@grupo&grupo_id={$grupo_id}") ?>"
                        class="btn btn-outline-secondary"
                    >
                        Cancelar
                    </a>

                </div>

            </form>

        </div>
    </div>

</div>

<!-- ✅ PREVIEW UX MELHORADO -->
<script>
const valorInput = document.querySelector('input[name="valor"]');
const preview = document.getElementById('preview');

valorInput.addEventListener('input', () => {
    const valor = parseFloat(valorInput.value || 0);

    if (valor > 0) {
        preview.style.display = 'block';

        preview.innerHTML = `
            <strong>Resumo:</strong><br>
            Valor solicitado: <strong>R$ ${valor.toFixed(2).replace('.', ',')}</strong><br>
            <span class="text-muted">
                A taxa e o valor total serão calculados automaticamente conforme as regras do grupo.
            </span>
        `;
    } else {
        preview.style.display = 'none';
    }
});
</script>

<!-- ✅ FASE 5.4 — PREVENÇÃO DUPLO ENVIO -->
<script>
document.getElementById('formEmprestimo').addEventListener('submit', function () {
    const btn = document.getElementById('btnSubmit');
    btn.disabled = true;
    btn.innerText = 'Enviando...';
});
</script>