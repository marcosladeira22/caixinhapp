
<h2>Dashboard do Administrador</h2>

<p>
    Você está gerenciando o grupo ID <strong><?= htmlspecialchars($grupo_id) ?></strong>
</p>

<div class="row mt-4">
    
</div>

<div class="row g-3 mt-3">

    <!-- Total em caixa -->
    <div class="col-md-3">
        <div class="card p-3 shadow h-100">
            <h5>Total em Caixa</h5>
            <p>R$ 0,00</p>
        </div>
    </div>

    <!-- Usuários -->
    <div class="col-md-3">
        <a href="<?= base_url("?rota=usuarioGrupo@index&grupo_id={$grupo_id}") ?>" 
           class="text-decoration-none text-dark">
            <div class="card p-3 shadow h-100">
                <h5>Usuários</h5>
                <p class="mb-0">Gerenciar membros do grupo</p>
            </div>
        </a>
    </div>

    <!-- Empréstimos -->
    <div class="col-md-3">
        <a href="<?= base_url("?rota=emprestimo@index&grupo_id={$grupo_id}") ?>" 
           class="text-decoration-none text-dark">
            <div class="card p-3 shadow h-100">
                <h5>Empréstimos</h5>
                <p class="mb-0">Solicitações, aprovações e histórico</p>
            </div>
        </a>
    </div>

    <!-- Inadimplentes -->
    <div class="col-md-3">
        <a href="<?= base_url("?rota=emprestimo@inadimplentes&grupo_id={$grupo_id}") ?>" 
           class="text-decoration-none text-dark">
            <div class="card p-3 shadow h-100">
                <h5>Inadimplentes</h5>
                <p class="mb-0">Usuários com atraso</p>
            </div>
        </a>
    </div>

    <!-- Pagamentos -->
    <div class="col-md-3">
        <a href="<?= base_url("?rota=pagamento@index&grupo_id={$grupo_id}") ?>" 
           class="text-decoration-none text-dark">
            <div class="card p-3 shadow h-100">
                <h5>Pagamentos</h5>
                <p class="mb-0">Histórico mensal</p>
            </div>
        </a>
    </div>

</div>