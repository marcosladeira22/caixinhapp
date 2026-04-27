
<div class="d-flex justify-content-between align-items-center mb-4">
<h3>Cadastrar Usuário no Grupo</h3>
    <?php require __DIR__ . '/../partials/voltar.php'; ?>
</div>
<hr>
<?php if (!empty($erro)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<form method="post" action="">

    <div class="mb-2">
        <label>Nome</label>
        <input type="text" name="nome" class="form-control" required>
    </div>

    <div class="mb-2">
        <label>E-mail</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="mb-2">
        <label>Telefone</label>
        <input type="text" name="telefone" class="form-control">
    </div>

    <div class="mb-2">
        <label>Sexo</label>
        <select name="sexo" class="form-control">
            <option value="O">Outro</option>
            <option value="M">Masculino</option>
            <option value="F">Feminino</option>
        </select>
    </div>

    <div class="mb-2">
        <label>Nível no Grupo</label>
        <select name="nivel" class="form-control">
            <option value="MEMBRO">Membro</option>
            <option value="ADMIN">Administrador</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Quantidade de Cotas</label>
        <input type="number" name="quantidade_cotas" value="1" min="1" class="form-control">
    </div>

    <button class="btn btn-success">Salvar</button>
    <a href="/?rota=dashboard@index&grupo_id=<?= $grupo_id ?>" class="btn btn-secondary">Voltar</a>
</form>

