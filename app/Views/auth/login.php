<div class="container mt-5">

    <div class="row justify-content-center">
        <div class="col-md-5">

            <div class="card shadow">
                <div class="card-body">

                    <h4 class="text-center mb-4 fw-bold">Login</h4>

                    <?php require __DIR__ . '/../components/alert.php'; ?>

                    <form method="post" id="formLogin">

                        <!-- CSRF -->
                        <input type="hidden" name="_token" value="<?= \Core\Csrf::gerarToken() ?>">

                        <!-- EMAIL -->
                        <div class="mb-3">
                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                placeholder="Seu e-mail"
                                required
                            >
                        </div>

                        <!-- SENHA -->
                        <div class="mb-3">
                            <input
                                type="password"
                                name="senha"
                                class="form-control"
                                placeholder="Sua senha"
                                required
                            >
                        </div>

                        <!-- BOTÃO -->
                        <button
                            type="submit"
                            class="btn btn-primary w-100 d-flex justify-content-center align-items-center"
                            id="btnSubmit"
                        >
                            Entrar
                        </button>

                    </form>

                    <!-- LINK CADASTRO -->
                    <div class="text-center mt-3">
                        <a href="<?= base_url('?rota=auth@cadastro') ?>">
                            Quero criar minha caixinha
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>

</div>

<!-- ✅ PREVENÇÃO DUPLO ENVIO -->
<script>
document.getElementById('formLogin').addEventListener('submit', function () {
    const btn = document.getElementById('btnSubmit');
    btn.disabled = true;
    btn.innerText = 'Entrando...';
});
</script>