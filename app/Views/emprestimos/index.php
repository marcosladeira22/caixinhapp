<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold m-0">Empréstimos do grupo</h4>
        <?php require __DIR__ . '/../partials/voltar.php'; ?>
    </div>

    <?php require __DIR__ . '/../components/alert.php'; ?>

    <?php if (!empty($emprestimos)): ?>

        <div class="card shadow">
            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-striped table-bordered align-middle">

                        <thead class="table-light text-center">
                            <tr>
                                <th>Usuário</th>
                                <th>Valor</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Vencimento</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($emprestimos as $e): ?>

                                <tr>
                                    <td><?= htmlspecialchars($e['nome']) ?></td>

                                    <td class="text-center">
                                        R$ <?= number_format($e['valor_solicitado'], 2, ',', '.') ?>
                                    </td>

                                    <td class="text-center">
                                        R$ <?= number_format($e['valor_total'], 2, ',', '.') ?>
                                    </td>

                                    <td class="text-center">
                                        <?php if ($e['status'] === 'APROVADO'): ?>
                                            <span class="badge bg-success">Aprovado</span>
                                        <?php elseif ($e['status'] === 'PENDENTE'): ?>
                                            <span class="badge bg-warning text-dark">Pendente</span>
                                        <?php elseif ($e['status'] === 'ATRASADO'): ?>
                                            <span class="badge bg-danger">Atrasado</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">
                                                <?= htmlspecialchars($e['status']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center">
                                        <?= !empty($e['data_vencimento'])
                                            ? htmlspecialchars($e['data_vencimento'])
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

        <!-- ✅ EMPTY STATE -->
        <div class="text-center p-5 bg-white shadow rounded">
            <h5 class="text-muted">Nenhum empréstimo encontrado</h5>
            <p class="text-muted">Não há registros para exibir no momento.</p>
        </div>

    <?php endif; ?>

    <!-- ✅ PAGINAÇÃO -->
    <?php
    $rota = 'emprestimo@index';
    $extras = [];
    require __DIR__ . '/../partials/paginator.php';
    ?>

</div>