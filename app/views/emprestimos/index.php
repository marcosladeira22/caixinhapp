<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h4>💸 Empréstimos</h4>
        </div>
        <div>
            <a href="<?= BASE_URL ?>/grupos/<?= $grupo_id ?>" 
                class="btn btn-outline-secondary mb-3">
                    ← Voltar
            </a>
        </div>
    </div>
    <hr>
    <a href="<?= BASE_URL ?>/emprestimos/create?grupo_id=<?= $grupo_id ?>" 
       class="btn btn-primary mb-3">
        + Novo Empréstimo
    </a>
    <div class="table-responsive">
        <?php if (empty($emprestimos)): ?>
            <p>Nenhum empréstimo</p>
        <?php else: ?>
            <table class="table table-bordered align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <th>Usuário</th>
                        <th>Valor</th>
                        <th>Com Juros</th>
                        <th>Vencimento</th>
                        <th>Status</th>
                        <th>Dias Atraso</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($emprestimos as $e): ?>
                    <?php
                        $diasAtraso = 0;
                        if ($e['status'] !== 'pago') {
                            $hoje = date('Y-m-d');
                            if ($hoje > $e['data_vencimento']) {
                                $diasAtraso = (strtotime($hoje) - strtotime($e['data_vencimento'])) / 86400;
                            }
                        }
                    ?>
                    <tr>
                        <td>
                            <strong><?= $e['nome'] ?></strong>
                        </td>
                        <td>
                            R$ <?= number_format($e['valor'], 2, ',', '.') ?>
                        </td>
                        <td>
                            <strong>
                                R$ <?= number_format($e['valor_com_juros'], 2, ',', '.') ?>
                            </strong>
                        </td>
                        <td class="text-center">
                            <?= date('d/m/Y', strtotime($e['data_vencimento'])) ?>
                        </td>
                        <td class="text-center">
                            <?php if ($e['status'] === 'aberto'): ?>
                                <span class="badge bg-primary">Aberto</span>

                            <?php elseif ($e['status'] === 'atrasado'): ?>
                                <span class="badge bg-danger">Atrasado</span>

                            <?php else: ?>
                                <span class="badge bg-success">Pago</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if ($diasAtraso > 0): ?>
                                <span class="badge bg-danger">
                                    <?= (int)$diasAtraso ?> dias
                                </span>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <a href="<?= BASE_URL ?>/emprestimos/edit?id=<?= $e['id'] ?>&grupo_id=<?= $grupo_id ?>"
                                    class="btn btn-sm btn-warning">✏️</a>

                            <a href="<?= BASE_URL ?>/emprestimos/pagar?id=<?= $e['id'] ?>&grupo_id=<?= $grupo_id ?>"
                                    class="btn btn-sm btn-success">✔</a>

                            <a href="<?= BASE_URL ?>/emprestimos/delete?id=<?= $e['id'] ?>&grupo_id=<?= $grupo_id ?>"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Excluir empréstimo?')">🗑
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>