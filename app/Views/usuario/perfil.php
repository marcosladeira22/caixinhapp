<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold m-0">Meu perfil</h4>
        <?php require __DIR__ . '/../partials/voltar.php'; ?>
    </div>

    <?php require __DIR__ . '/../components/alert.php'; ?>

    <div class="card shadow">
        <div class="card-body">

            <!-- ✅ FORM CORRETO -->
            <form method="post" action="<?= base_url('?rota=usuario@atualizar') ?>" id="formPerfil">

                <!-- ✅ CSRF -->
                <input type="hidden" name="_token" value="<?= \Core\Csrf::gerarToken() ?>">

                <!-- NOME -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nome</label>
                    <input
                        type="text"
                        name="nome"
                        class="form-control"
                        value="<?= htmlspecialchars($usuario['nome'] ?? '') ?>"
                        required
                    >
                </div>

                <!-- EMAIL -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">E-mail</label>
                    <input
                        type="email"
                        class="form-control"
                        value="<?= htmlspecialchars($usuario['email'] ?? '') ?>"
                        disabled
                    >
                </div>

                <!-- TELEFONE -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Telefone</label>
                    <input
                        type="text"
                        name="telefone"
                        class="form-control"
                        value="<?= htmlspecialchars($usuario['telefone'] ?? '') ?>"
                    >
                </div>

                <!-- SEXO -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Sexo</label>
                    <select name="sexo" class="form-select">

                        <option value="M" <?= ($usuario['sexo'] ?? '') === 'M' ? 'selected' : '' ?>>
                            Masculino
                        </option>

                        <option value="F" <?= ($usuario['sexo'] ?? '') === 'F' ? 'selected' : '' ?>>
                            Feminino
                        </option>

                        <option value="O" <?= ($usuario['sexo'] ?? '') === 'O' ? 'selected' : '' ?>>
                            Outro
                        </option>

                    </select>
                </div>

                <!-- SENHA -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">Nova senha (opcional)</label>
                    <input
                        type="password"
                        name="senha"
                        class="form-control"
                        placeholder="Deixe em branco para não alterar"
                    >
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
document.getElementById('formPerfil').addEventListener('submit', function () {
    const btn = document.getElementById('btnSubmit');
    btn.disabled = true;
    btn.innerText = 'Salvando...';
});
</script>