<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold m-0">Pagamentos de cotas</h4>
        <?php require __DIR__ . '/../partials/voltar.php'; ?>
    </div>

    <?php require __DIR__ . '/../components/alert.php'; ?>

    <?php if (!empty($pagamentos)): ?>

        <div class="card shadow">
            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-striped table-bordered align-middle">

                        <thead class="table-light text-center">
                            <tr>
                                <th>Usuário</th>
                                <th>Cotas</th>
                                <th>Status</th>
                                <th style="width: 180px;">Ação</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($pagamentos as $p): ?>

                                <tr>
                                    <td><?= htmlspecialchars($p['nome']) ?></td>

                                    <td class="text-center">
                                        <?= (int)$p['quantidade_cotas'] ?>
                                    </td>

                                    <td class="text-center">
                                        <?php if (!empty($p['pagamento_id'])): ?>
                                            <span class="badge bg-success">Pago</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Em atraso</span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center">

                                        <?php if (empty($p['pagamento_id'])): ?>

                                            <form 
                                                method="post"
                                                action="<?= base_url('?rota=pagamento@pagar') ?>"
                                                class="form-pagar"
                                            >

                                                <!-- ✅ CSRF -->
                                                <input type="hidden" name="_token" value="<?= \Core\Csrf::gerarToken() ?>">

                                                <input type="hidden" name="grupo_id" value="<?= (int)$grupo_id ?>">
                                                <input type="hidden" name="usuario_id" value="<?= (int)$p['usuario_id'] ?>">

                                                <button 
                                                    type="submit" 
                                                    class="btn btn-success btn-sm w-100"
                                                >
                                                    Marcar como pago
                                                </button>

                                            </form>

                                        <?php else: ?>

                                            <span class="text-muted">—</span>

                                        <?php endif; ?>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            </div>
        </div>

    <?php else: ?>

        <!-- ✅ EMPTY STATE -->
        <div class="text-center p-5 bg-white shadow rounded">
            <h5 class="text-muted">Nenhum pagamento encontrado</h5>
            <p class="text-muted">Ainda não há registros para exibir.</p>
        </div>

    <?php endif; ?>

    <!-- ✅ PAGINAÇÃO -->
    <?php
    $rota = 'pagamento@index';
    $extras = [];
    require __DIR__ . '/../partials/paginator.php';
    ?>

</div>

<!-- ✅ FASE 5.4 — PREVENÇÃO DUPLO CLIQUE -->
<script>
document.querySelectorAll('.form-pagar').forEach(form => {
    form.addEventListener('submit', function () {
        const btn = this.querySelector('button');
        btn.disabled = true;
        btn.innerText = 'Processando...';
    });
});
</script>