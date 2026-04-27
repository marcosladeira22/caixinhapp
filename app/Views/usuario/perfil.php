
<div class="d-flex justify-content-between align-items-center mb-4">
<h3>Meu Perfil</h3>
    <?php require __DIR__ . '/../partials/voltar.php'; ?>
</div>
<hr>
<form action="<?= base_url('?rota=usuario@atualizar') ?>" method="post">

    <div class="mb-3">
        <label class="form-label">Nome</label>
        <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($usuario['nome']) ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">E-mail</label>
        <input type="email" class="form-control" value="<?= htmlspecialchars($usuario['email']) ?>" disabled>
    </div>

    <div class="mb-3">
        <label class="form-label">Telefone</label>
        <input type="text" name="telefone" class="form-control" value="<?= htmlspecialchars($usuario['telefone'] ?? '') ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Sexo</label>
        <select name="sexo" class="form-select">
            <option value="M" <?= $usuario['sexo'] === 'M' ? 'selected' : '' ?>>Masculino</option>
            <option value="F" <?= $usuario['sexo'] === 'F' ? 'selected' : '' ?>>Feminino</option>
            <option value="O" <?= $usuario['sexo'] === 'O' ? 'selected' : '' ?>>Outro</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Nova Senha (opcional)</label>
        <input type="password" name="senha" class="form-control">
    </div>

    <button class="btn btn-primary">Salvar</button>
</form>