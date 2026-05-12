<div class="d-flex justify-content-between align-items-center mb-4">

    <h3>Inadimplentes do grupo</h3>
    <a href="<?= base_url('?rota=dashboard@grupo&grupo_id=' . $grupo_id) ?>" class="btn btn-secondary">
        ← Voltar ao grupo
    </a>
</div>
<hr>
    <?php if (!empty($lista)): ?>

        <div class="card shadow">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle">

                        <thead class="table-dark">
                            <tr>
                                <th>Usuário</th>
                                <th>Valor Solicitado</th>
                                <th>Valor Total</th>
                                <th>Status</th>
                                <th>Vencimento</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($lista as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['nome']) ?></td>

                                    <td>
                                        R$ <?= number_format($item['valor_solicitado'], 2, ',', '.') ?>
                                    </td>

                                    <td>
                                        R$ <?= number_format($item['valor_total'], 2, ',', '.') ?>
                                    </td>

                                    <td class="text-danger fw-bold">
                                        <?= htmlspecialchars($item['status']) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($item['data_vencimento'] ?? '-') ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>

                    </table>
                </div>

            </div>
        </div>

    <?php else: ?>

        <div class="alert alert-success">
            Nenhum inadimplente neste grupo ✅
        </div>

    <?php endif; ?>

</div>