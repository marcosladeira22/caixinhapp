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
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5><?= $grupo['nome'] ?></h5>
                            <p class="text-muted">
                                <?= $grupo['descricao'] ?>
                            </p>
                            <p>
                                💰 Cota: R$ <?= number_format($grupo['valor_cota'], 2, ',', '.') ?>
                            </p>
                            <p>
                                👤 Nível: 
                                <strong>
                                    <?= $grupo['nivel'] === 'admin' ? 'Administrador' : 'Membro' ?>
                                </strong>
                            </p>

                            <!-- Botão entrar -->
                            <a href="<?= BASE_URL ?>/grupos/<?= $grupo['id'] ?>" class="btn btn-success btn-sm"> Entrar no grupo</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>