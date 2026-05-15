<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold m-0">Cadastrar usuário no grupo</h4>
        <?php require __DIR__ . '/../partials/voltar.php'; ?>
    </div>

    <?php require __DIR__ . '/../components/alert.php'; ?>

    <div class="card shadow">
        <div class="card-body">

            <form method="post" id="formUsuarioGrupo">

                <!-- ✅ CSRF -->
                <input type="hidden" name="_token" value="<?= \Core\Csrf::gerarToken() ?>">

                <div class="row">

                    <!-- NOME -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Nome</label>
                        <input type="text" name="nome" class="form-control" required>
                    </div>

                    <!-- EMAIL -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">E-mail</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <!-- TELEFONE -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Telefone</label>
                        <input type="text" name="telefone" class="form-control">
                    </div>

                    <!-- SEXO -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Sexo</label>
                        <select name="sexo" class="form-select">
                            <option value="O">Outro</option>
                            <option value="M">Masculino</option>
                            <option value="F">Feminino</option>
                        </select>
                    </div>

                    <!-- NÍVEL -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Nível no grupo</label>
                        <select name="nivel" class="form-select">
                            <option value="MEMBRO">Membro</option>
                            <option value="ADMIN">Administrador</option>
                        </select>
                    </div>

                    <!-- COTAS -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold">Quantidade de cotas</label>
                        <input 
                            type="number" 
                            name="quantidade_cotas" 
                            value="1" 
                            min="1" 
                            class="form-control"
                        >
                    </div>

                </div>

                <!-- BOTÕES -->
                <div class="d-flex gap-2">

                    <button 
                        type="submit"
                        class="btn btn-success flex-grow-1 d-flex justify-content-center align-items-center"
                        id="btnSubmit"
                    >
                        Salvar usuário
                    </button>

                    <!-- ✅ LINK CORRIGIDO -->
                    <a 
                        href="<?= base_url('?rota=dashboard@grupo&grupo_id=' . (int)$grupo_id) ?>" 
                        class="btn btn-outline-secondary"
                    >
                        Cancelar
                    </a>

                </div>

            </form>

        </div>
    </div>

</div>

<!-- ✅ FASE 5.4 — PREVENÇÃO DUPLO ENVIO -->
<script>
document.getElementById('formUsuarioGrupo').addEventListener('submit', function () {
    const btn = document.getElementById('btnSubmit');
    btn.disabled = true;
    btn.innerText = 'Salvando...';
});
</script>