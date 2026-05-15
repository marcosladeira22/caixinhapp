<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold m-0">
            Dashboard do Grupo
        </h4>

        <?php require __DIR__ . '/../partials/voltar.php'; ?>
    </div>

    <?php require __DIR__ . '/../components/alert.php'; ?>

    <?php if (!empty($dados) && $dados['tipo'] === 'ADMIN'): ?>

        <!-- ✅ PROFILE ADMIN -->
        <div class="row g-3">

            <!-- Total em caixa -->
            <div class="col-md-3">
                <div class="card shadow h-100 text-center">
                    <div class="card-body">
                        <h6 class="text-muted">Total em caixa</h6>
                        <h5 class="fw-bold">R$ 0,00</h5>
                    </div>
                </div>
            </div>

            <!-- Usuários -->
            <div class="col-md-3">
                <a href="<?= base_url("?rota=usuarioGrupo@index&grupo_id={$dados['grupo_id']}") ?>"
                   class="text-decoration-none">
                    
                    <div class="card shadow h-100 hover-card">
                        <div class="card-body">
                            <h6 class="fw-bold">Usuários</h6>
                            <p class="text-muted small mb-0">Gerenciar membros</p>
                        </div>
                    </div>

                </a>
            </div>

            <!-- Pagamentos -->
            <div class="col-md-3">
                <a href="<?= base_url("?rota=pagamento@index&grupo_id={$dados['grupo_id']}") ?>"
                   class="text-decoration-none">
                    
                    <div class="card shadow h-100 hover-card">
                        <div class="card-body">
                            <h6 class="fw-bold">Pagamentos</h6>
                            <p class="text-muted small mb-0">Cotas mensais</p>
                        </div>
                    </div>

                </a>
            </div>

            <!-- Empréstimos -->
            <div class="col-md-3">
                <a href="<?= base_url("?rota=emprestimo@index&grupo_id={$dados['grupo_id']}") ?>"
                   class="text-decoration-none">
                    
                    <div class="card shadow h-100 hover-card">
                        <div class="card-body">
                            <h6 class="fw-bold">Empréstimos</h6>
                            <p class="text-muted small mb-0">Solicitações e histórico</p>
                        </div>
                    </div>

                </a>
            </div>

            <!-- Inadimplentes -->
            <div class="col-md-3">
                <a href="<?= base_url("?rota=emprestimo@inadimplentes&grupo_id={$dados['grupo_id']}") ?>"
                   class="text-decoration-none">
                    
                    <div class="card shadow h-100 hover-card">
                        <div class="card-body">
                            <h6 class="fw-bold text-danger">Inadimplentes</h6>
                            <p class="text-muted small mb-0">Usuários com atraso</p>
                        </div>
                    </div>

                </a>
            </div>

            <!-- Relatórios -->
            <div class="col-md-3">
                <a href="<?= base_url("?rota=relatorio@financeiro&grupo_id={$dados['grupo_id']}") ?>"
                   class="text-decoration-none">
                    
                    <div class="card shadow h-100 hover-card">
                        <div class="card-body">
                            <h6 class="fw-bold">Relatórios</h6>
                            <p class="text-muted small mb-0">Financeiro do grupo</p>
                        </div>
                    </div>

                </a>
            </div>

            <!-- Logs -->
            <div class="col-md-3">
                <a href="<?= base_url("?rota=log@index&grupo_id={$dados['grupo_id']}") ?>"
                   class="text-decoration-none">
                    
                    <div class="card shadow h-100 hover-card">
                        <div class="card-body">
                            <h6 class="fw-bold">Logs</h6>
                            <p class="text-muted small mb-0">Auditoria</p>
                        </div>
                    </div>

                </a>
            </div>

        </div>

    <?php elseif (!empty($dados)): ?>

        <!-- ✅ PROFILE MEMBRO -->
        <div class="row g-3">

            <div class="col-md-4">
                <div class="card shadow h-100 text-center">
                    <div class="card-body">
                        <h6 class="text-muted">Minhas cotas</h6>
                        <h5 class="fw-bold"><?= htmlspecialchars($dados['quantidade_cotas']) ?></h5>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow h-100 text-center">
                    <div class="card-body">
                        <h6 class="text-muted">Meu score</h6>
                        <h5 class="fw-bold"><?= htmlspecialchars($dados['score']) ?></h5>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <a href="<?= base_url("?rota=emprestimo@index&grupo_id={$dados['grupo_id']}") ?>"
                   class="text-decoration-none">

                    <div class="card shadow h-100 hover-card">
                        <div class="card-body">
                            <h6 class="fw-bold">Meus empréstimos</h6>
                            <p class="text-muted small mb-0">Acompanhar solicitações</p>
                        </div>
                    </div>

                </a>
            </div>

        </div>

    <?php else: ?>

        <!-- ✅ EMPTY STATE -->
        <div class="text-center p-5 bg-white shadow rounded">
            <h5 class="text-muted">Nenhum dado disponível</h5>
            <p class="text-muted">Não foi possível carregar o dashboard.</p>
        </div>

    <?php endif; ?>

</div>