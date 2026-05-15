<div class="container mt-4">

    <div class="card shadow">
        <div class="card-body">

            <h4 class="fw-bold mb-4">
                Selecione um grupo
            </h4>

            <?php require __DIR__ . '/../components/alert.php'; ?>

            <?php if (!empty($grupos)): ?>

                <div class="list-group">

                    <?php foreach ($grupos as $grupo): ?>

                        <div class="list-group-item d-flex justify-content-between align-items-center">

                            <div>
                                <strong><?= htmlspecialchars($grupo['nome']) ?></strong>
                            </div>

                            <a 
                                href="<?= base_url("?rota=dashboard@grupo&grupo_id={$grupo['id']}") ?>"
                                class="btn btn-primary btn-sm"
                            >
                                Entrar
                            </a>

                        </div>

                    <?php endforeach; ?>

                </div>

            <?php else: ?>

                <!-- ✅ EMPTY STATE -->
                <div class="text-center p-5">
                    <h5 class="text-muted">Você ainda não participa de nenhum grupo</h5>

                    <a 
                        href="<?= base_url('?rota=grupo@criar') ?>"
                        class="btn btn-success mt-3"
                    >
                        Criar meu primeiro grupo
                    </a>
                </div>

            <?php endif; ?>

        </div>
    </div>

</div>
