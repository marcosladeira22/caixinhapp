<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Editar Usuário do Grupo</h3>
    <?php require __DIR__ . '/../partials/voltar.php'; ?>
</div>
<hr>

<form action="<?= base_url('?rota=usuarioGrupo@atualizar') ?>" method="post"></form>

    <input type="hidden" name="id" value="<?= $registro['id'] ?>">

    <div class="mb-3">
        <label class="form-label">Quantidade de Cotas</label>
        <input type="number" name="quantidade_cotas" class="form-control" value="<?= $registro['quantidade_cotas'] ?>" min="1" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Nível</label>
        <select name="nivel" class="form-select">
            <option value="MEMBRO" <?= $registro['nivel'] === 'MEMBRO' ? 'selected' : '' ?>>
                Membro
            </option>
            <option value="ADMIN" <?= $registro['nivel'] === 'ADMIN' ? 'selected' : '' ?>>
                Administrador
            </option>
        </select>
    </div>

    <div class="form-check mb-3">
        <input type="checkbox" name="ativo" class="form-check-input" id="ativo" <?= $registro['ativo'] ? 'checked' : '' ?>>
        <label class="form-check-label" for="ativo">
            Usuário ativo no grupo
        </label>
    </div>
    <button class="btn btn-primary">Salvar Alterações</button>
</form>