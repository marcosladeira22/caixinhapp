<div class="container mt-4">
    <h3>Meu Perfil</h3>

    <form method="POST" action="<?= BASE_URL ?>/perfil/salvar">

        <div class="mb-3">
            <label>Nome</label>
            <input type="text" name="nome" class="form-control"
                   value="<?= $usuario['nome'] ?>" required>
        </div>

        <div class="mb-3">
            <label>E-mail</label>
            <input type="email" class="form-control"
                   value="<?= $usuario['email'] ?>" disabled>
        </div>

        <div class="mb-3">
            <label>Nova Senha (opcional)</label>
            <input type="password" name="senha" class="form-control">
        </div>

        <button class="btn btn-primary">Salvar</button>

    </form>
</div>