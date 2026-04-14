<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow" style="width: 400px;">
        <h3 class="text-center mb-3">

            <!-- FORMULÁRIO -->
            <form action="<?= BASE_URL ?>/registrar" method="POST">

                <!-- Nome -->
                <div class="mb-3">
                    <label for="">Nome</label>
                    <input class="form-control" type="text" name="nome" id="" required placeholder="Informe seu nome">
                </div>

                <!-- E-mail -->
                <div class="mb-3">
                    <label for="">E-mail</label>
                    <input class="form-control" type="email" name="email" id="" required placeholder="Informe seu e-mail">
                </div>

                <!-- Senha -->
                <div class="mb-3">
                    <label for="">Senha</label>
                    <input class="form-control" type="password" name="senha" id="" required placeholder="Informe sua senha">
                </div>
                
                <!-- Botão -->
                 <button class="btn btn-success w-100">Cadastrar</button>

                <!-- Link -->
                <a href="<?= BASE_URL ?>/" class="btn btn-link w-100 mt-2">Já tenho conta</a> 
            </form> 
        </h3>
    </div>
</div>