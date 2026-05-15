<div class="container mt-4">

    <h4 class="fw-bold mb-4">Fechamento da caixinha</h4>

    <?php require __DIR__ . '/../components/alert.php'; ?>

    <div class="card shadow">
        <div class="card-body">

            <!-- ALERTA -->
            <div class="alert alert-warning">
                <strong>Atenção:</strong> Após o fechamento, nenhuma operação será permitida.
            </div>

            <!-- ✅ FORM CORRETO -->
            <form method="post" action="<?= base_url('?rota=fechamento@fechar') ?>" id="formFechamento">

                <!-- CSRF -->
                <input type="hidden" name="_token" value="<?= \Core\Csrf::gerarToken() ?>">

                <!-- GRUPO -->
                <input type="hidden" name="grupo_id" value="<?= isset($grupo_id) ? (int)$grupo_id : 0 ?>">

                <!-- BOTÕES -->
                <div class="d-flex gap-2 mt-3">

                    <button 
                        type="submit" 
                        class="btn btn-danger flex-grow-1"
                        id="btnFechar"
                    >
                        Confirmar fechamento
                    </button>

                    <a href="<?= base_url('?rota=dashboard@grupo&grupo_id=' . (int)$grupo_id) ?>" 
                       class="btn btn-outline-secondary">
                        Cancelar
                    </a>

                </div>

            </form>

        </div>
    </div>

</div>

<!-- ✅ PREVENÇÃO DUPLO CLIQUE + CONFIRMAÇÃO -->
<script>
document.getElementById('formFechamento').addEventListener('submit', function (e) {

    const confirmar = confirm('Tem certeza que deseja fechar o grupo? Essa ação não pode ser desfeita.');

    if (!confirmar) {
        e.preventDefault();
        return;
    }

    const btn = document.getElementById('btnFechar');
    btn.disabled = true;
    btn.innerText = 'Fechando...';
});
</script>