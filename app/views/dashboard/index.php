<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Meus Grupos</h3>

        <!-- Botão criar grupo -->
        <a href="<?= BASE_URL ?>/grupos/create" class="btn btn-primary">+ Novo Grupo</a> 
    </div>
    <?php if(empty($grupos)): ?>
        <div class="alert alert-info">
            Você ainda não participa de nenhum grupo.
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($grupos as $grupo): ?>
                <div class="col-md-6 mb-3"> <!-- Aumentei para col-md-6 para caber melhor o conteúdo lado a lado -->
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <!-- Lado Esquerdo: Nome e Descrição -->
                                <div class="col-7">
                                    <h5 class="mb-1"><?= $grupo['nome'] ?></h5>
                                    <p class="text-muted small mb-0">
                                        <?= $grupo['descricao'] ?>
                                    </p>
                                </div>

                                <!-- Linha Vertical e Lado Direito: Cota e Nível -->
                                <div class="col-5 border-start ps-3">
                                    <p class="mb-1 small">
                                        💰 <strong>R$ <?= number_format($grupo['valor_cota'], 2, ',', '.') ?></strong>
                                    </p>
                                    <p class="mb-0 small">
                                        👤 <?= $grupo['nivel'] === 'admin' ? 'Admin' : 'Membro' ?>
                                    </p>
                                </div>
                            </div>

                            <!-- Botão entrar -->
                            <div class="mt-3">
                                <a href="<?= BASE_URL ?>/grupos/<?= $grupo['id'] ?>" class="btn btn-success btn-sm w-100">Entrar no grupo</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>