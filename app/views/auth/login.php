
<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow" style="width: 400px;">
        <h3 class="text-center mb-3">Login</h3>

        <!-- Formulário -->
        <form action="<?= BASE_URL ?>/login" method="POST">

            <!-- Email -->
            <div class="mb-3">
                <label for="">E-mail</label>
                <input class="form-control" type="email" name="email" id="" required placeholder="Informe o e-mail">
            </div>

            <!-- Senha -->
            <div class="mb-3">
                <label for="">Senha</label>
                <input class="form-control" type="password" name="senha" id="" required placeholder="Informe a senha">
            </div>

            <!-- Botão -->
            <button class="btn btn-primary w-100" type="submit">Entrar</button>

            <!-- Criar conta -->
            <a href="<?= BASE_URL ?>/register" class="btn btn-link w-100 mt-2">
                Criar conta
            </a>
        </form>
    </div>
</div>