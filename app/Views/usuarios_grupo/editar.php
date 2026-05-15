<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold m-0">Editar usuário do grupo</h4>
        <?php require __DIR__ . '/../partials/voltar.php'; ?>
    </div>

    <?php require __DIR__ . '/../components/alert.php'; ?>

    <div class="card shadow">
        <div class="card-body">

            <!-- ✅ FORM CORRETO -->
            <form action="<?= base_url('?rota=usuarioGrupo@atualizar') ?>" method="post" id="formEditar">

                <!-- CSRF -->
                <input type="hidden" name="_token" value="<?= \Core\Csrf::gerarToken() ?>">

                <!-- ID -->
                <input type="hidden" name="id" value="<?= isset($registro['id']) ? (int)$registro['id'] : 0 ?>">

                <!-- COTAS -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Quantidade de cotas</label>
                    <input 
                        type="number" 
                        name="quantidade_cotas" 
                        class="form-control" 
                        value="<?= isset($registro['quantidade_cotas']) ? (int)$registro['quantidade_cotas'] : 1 ?>" 
                        min="1" 
                        required
                    >
                </div>

                <!-- NÍVEL -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nível</label>
                    <select name="nivel" class="form-select">

                        <option value="MEMBRO" <?= ($registro['nivel'] ?? '') === 'MEMBRO' ? 'selected' : '' ?>>
                            Membro
                        </option>

                        <option value="ADMIN" <?= ($registro['nivel'] ?? '') === 'ADMIN' ? 'selected' : '' ?>>
                            Administrador
                        </option>

                    </select>
                </div>

                <!-- ATIVO -->
                <div class="form-check mb-4">
                    <input 
                        type="checkbox" 
                        name="ativo" 
                        class="form-check-input" 
                        id="ativo"
                        <?= !empty($registro['ativo']) ? 'checked' : '' ?>
                    >
                    <label class="form-check-label" for="ativo">
                        Usuário ativo no grupo
                    </label>
                </div>

                <!-- BOTÕES -->
                <div class="d-flex gap-2">

                    <button 
                        type="submit"
                        class="btn btn-primary flex-grow-1 d-flex justify-content-center align-items-center"
                        id="btnSubmit"
                    >
                        Salvar alterações
                    </button>

                    <a href="<?= base_url('?rota=dashboard@index') ?>" 
                       class="btn btn-outline-secondary">
                        Cancelar
                    </a>

                </div>

            </form>

        </div>
    </div>

</div>

<!-- ✅ FASE 5.4 — PREVENÇÃO DUPLO ENVIO -->
<script>
document.getElementById('formEditar').addEventListener('submit', function () {
    const btn = document.getElementById('btnSubmit');
    btn.disabled = true;
    btn.innerText = 'Salvando...';
});
</script>