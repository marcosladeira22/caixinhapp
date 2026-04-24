<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Dashboard do Administrador</h3>
    <?php require __DIR__ . '/../partials/voltar.php'; ?>
</div>
<hr>

<?php if ($dados['tipo'] === 'ADMIN'): ?>

    <!-- VISÃO DO ADMINISTRADOR -->
    <div class="row g-3 mt-3">
        
        <!-- Total em caixa -->
        <div class="col-md-3">
            <div class="card p-3 shadow h-100">
                <h5>Total em Caixa</h5>
                <p>R$ 0,00</p>
            </div>
        </div>

        <!-- Usuário -->
        <div class="col-md-3">
            <a href=" <?= base_url("?rota=usuarioGrupo@index&grupo_id={$dados['grupo_id']}") ?>" class="text-decoration-none text-dark">
                <div class="card p-3 shadow h-100">
                    <h5>Usuários</h5>
                    <p>Gerenciar membros</p>
                </div>
            </a>
        </div>

        <!-- Pagamentos -->
        <div class="col-md-3">
            <a href="<?= base_url("?rota=pagamento@index&grupo_id={$dados['grupo_id']}") ?>" class="text-decoration-none text-dark">
                <div class="card p-3 shadow h-100">
                    <h5>Pagamentos</h5>
                    <p>Cotas mensais</p>
                </div>
            </a>
        </div>

        <!-- Empréstimo -->
        <div class="col-md-3">
            <a href="<?= base_url("?rota=emprestimo@index&grupo_id={$dados['grupo_id']}") ?>" class="text-decoration-none text-dark">
                <div class="card p-3 shadow h-100">
                    <h5>Empréstimos</h5>
                    <p>Solicitações e histórico</p>
                </div>
            </a>
        </div>

        <!-- Inadimplentes -->
        <div class="col-md-3">
            <a href="<?= base_url("?rota=emprestimo@inadimplentes&grupo_id={$dados['grupo_id']}") ?>" class="text-decoration-none text-dark">
                <div class="card p-3 shadow h-100">
                    <h5>Inadimplentes</h5>
                    <p class="mb-0">Usuários com atraso</p>
                </div>
            </a>
        </div>

        <!-- Relatórios -->
        <div class="col-md-3">
        <a href="<?= base_url("?rota=relatorio@financeiro&grupo_id={$dados['grupo_id']}") ?>" class="text-decoration-none text-dark">        
            <div class="card p-3 shadow h-100">
                <h5>Relatórios</h5>
                <p class="mb-0">Financeiro e fechamento</p>
            </div>
        </a>
    </div>

    </div>

<?php else: ?>

    <!-- VISÃO DO MEMBRO -->
    <div class="row g-3 mt-3">

        <div class="col-md-4">
            <div class="card p-3 shadow">
                <strong>Minhas Cotas</strong>
                <p><?= $dados['quantidade_cotas'] ?></p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3 shadow">
                <strong>Meu Score</strong>
                <p><?= $dados['score'] ?></p>
            </div>
        </div>

        <div class="col-md-4">
            <a href="<?= base_url("?rota=emprestimo@index&grupo_id={$dados['grupo_id']}") ?>" class="text-decoration-none text-dark">
                <div class="card p-3 shadow h-100">
                    <h5>Meus Empréstimos</h5>
                    <p>Acompanhar solicitações</p>
                </div>
            </a>
        </div>

    </div>

<?php endif; ?>
