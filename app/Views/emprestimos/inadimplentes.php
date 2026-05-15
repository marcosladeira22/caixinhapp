<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold m-0">Inadimplentes do grupo</h4>

        <a href="<?= base_url("?rota=dashboard@grupo&grupo_id={$grupo_id}") ?>" 
           class="btn btn-outline-secondary">
            ← Voltar
        </a>
    </div>

    <?php require __DIR__ . '/../components/alert.php'; ?>

    <?php if (!empty($lista)): ?>

        <div class="card shadow">
            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-striped table-bordered align-middle">

                        <thead class="table-light text-center">
                            <tr>
                                <th>Usuário</th>
                                <th>Valor solicitado</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Vencimento</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($lista as $item): ?>

                                <tr>

                                    <td>
                                        <?= htmlspecialchars($item['nome']) ?>
                                    </td>

                                    <td class="text-center">
                                        R$ <?= number_format($item['valor_solicitado'], 2, ',', '.') ?>
                                    </td>

                                    <td class="text-center">
                                        <strong>
                                            R$ <?= number_format($item['valor_total'], 2, ',', '.') ?>
                                        </strong>
                                    </td>

                                    <td class="text-center">
                                        <span class="badge bg-danger">
                                            <?= htmlspecialchars($item['status']) ?>
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        <?= !empty($item['data_vencimento']) 
                                            ? htmlspecialchars($item['data_vencimento']) 
                                            : '-' ?>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            </div>
        </div>

    <?php else: ?>

        <!-- ✅ EMPTY STATE MELHORADO -->
        <div class="text-center p-5 bg-white shadow rounded">
            <h5 class="text-success">Nenhum inadimplente 🎉</h5>
            <p class="text-muted mb-0">
                Todos os usuários estão em dia com seus pagamentos.
            </p>
        </div>

    <?php endif; ?>

</div>