<div class="container mt-5">
    
    <div class="row justify-content-center">
        <div class="col-md-5">

            <div class="card shadow">
                <div class="card-body">

                    <h4 class="text-center mb-4 fw-bold">
                        Criar conta
                    </h4>

                    <!-- ✅ ALERT GLOBAL (usa seu componente) -->
                    <?php require __DIR__ . '/../components/alert.php'; ?>

                    <form method="post" id="formCadastro">

                        <!-- ✅ CSRF seguro -->
                        <input type="hidden" name="_token" value="<?= \Core\Csrf::gerarToken() ?>">

                        <!-- NOME -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nome</label>
                            <input 
                                type="text" 
                                name="nome" 
                                class="form-control" 
                                placeholder="Seu nome completo" 
                                required
                            >
                        </div>

                        <!-- EMAIL -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">E-mail</label>
                            <input 
                                type="email" 
                                name="email" 
                                class="form-control" 
                                placeholder="seu@email.com"
                                required
                            >
                        </div>

                        <!-- SENHA -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Senha</label>
                            <input 
                                type="password" 
                                name="senha" 
                                class="form-control" 
                                placeholder="Sua senha"
                                required
                            >
                        </div>

                        <!-- TELEFONE -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Telefone</label>
                            <input 
                                type="text" 
                                name="telefone" 
                                class="form-control" 
                                placeholder="Opcional"
                            >
                        </div>

                        <!-- SEXO -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Sexo</label>
                            <select class="form-select" name="sexo">
                                <option value="O">Prefiro não informar</option>
                                <option value="M">Masculino</option>
                                <option value="F">Feminino</option>
                            </select>
                        </div>

                        <!-- BOTÃO -->
                        <button 
                            class="btn btn-success w-100 d-flex justify-content-center align-items-center gap-2"
                            id="btnSubmit"
                        >
                            Criar conta
                        </button>

                    </form>

                    <!-- LINK LOGIN -->
                    <div class="text-center mt-4">
                        <a href="<?= base_url('?rota=auth@login') ?>">
                            Já tenho conta
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>

</div>

<!-- ✅ FASE 5.4 — PREVINE DUPLO CLIQUE -->
<script>
document.getElementById('formCadastro').addEventListener('submit', function () {
    const btn = document.getElementById('btnSubmit');
    btn.disabled = true;
    btn.innerText = 'Criando conta...';
});
</script>