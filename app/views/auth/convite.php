<div class="container mt-5">
    <div class="card p-4">
        <h3>Ativar Conta</h3>
        <p>Olá, <?= $usuario['nome'] ?></p>
        <form method="POST" action="<?= BASE_URL ?>/convite/aceitar">
            <input type="hidden" name="token" value="<?= $_GET['token'] ?>">
            <div class="mb-3">
                <label>Nova senha</label>
                <input type="password" name="senha" class="form-control" required>
            </div>
            <button class="btn btn-success">
                Ativar Conta
            </button>
        </form>
    </div>
</div>