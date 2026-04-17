<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <h4>💸 Empréstimos</h4>

        <a href="<?= BASE_URL ?>/grupos/<?= $grupo_id ?>" 
           class="btn btn-outline-secondary mb-3">
            ← Voltar
        </a>
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

        <table class="table table-bordered align-middle text-center">

            <thead class="table-light">
                <tr>
                    <th>Usuário</th>
                    <th>Valor</th>
                    <th>Com Juros</th>
                    <th>Vencimento</th>
                    <th>Status</th>
                    <th>Dias</th>
                    <th>Ação</th>
                </tr>
            </thead>

            <tbody>

            <?php foreach($emprestimos as $e): ?>

                <tr>

                    <td><strong><?= $e['nome'] ?></strong></td>

                    <td>R$ <?= number_format($e['valor'], 2, ',', '.') ?></td>

                    <td>
                        <strong>
                            R$ <?= number_format($e['valor_com_juros'], 2, ',', '.') ?>
                        </strong>
                    </td>

                    <td>
                        <?= date('d/m/Y', strtotime($e['data_vencimento'])) ?>
                    </td>

                    <!-- STATUS -->
                    <td>
                        <?php if ($e['status'] === 'pendente'): ?>
                            <span class="badge bg-warning text-dark">Pendente</span>

                        <?php elseif ($e['status'] === 'aberto'): ?>
                            <span class="badge bg-primary">Aberto</span>

                        <?php elseif ($e['status'] === 'atrasado'): ?>
                            <span class="badge bg-danger">Atrasado</span>

                        <?php else: ?>
                            <span class="badge bg-success">Pago</span>
                        <?php endif; ?>
                    </td>

                    <!-- DIAS -->
                    <td>
                        <?php if ($e['dias_atraso'] > 0): ?>
                            <span class="badge bg-danger">
                                <?= $e['dias_atraso'] ?> dias
                            </span>
                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </td>

                    <!-- AÇÕES -->
                    <td class="text-center">

                        <!-- ✏️ Editar -->
                        <a href="<?= BASE_URL ?>/emprestimos/edit?id=<?= $e['id'] ?>&grupo_id=<?= $grupo_id ?>"
                            class="btn btn-sm btn-warning">
                            ✏️
                        </a>

                        <!-- ✅ SE FOR PENDENTE → MOSTRA APROVAR/RECUSAR -->
                        <?php if ($e['status'] === 'pendente'): ?>

                            <?php if ($_SESSION['nivel'] === 'master' || ($_SESSION['nivel_grupo'] ?? '') === 'admin'): ?>

                                <a href="<?= BASE_URL ?>/emprestimos/aprovar?id=<?= $e['id'] ?>&grupo_id=<?= $grupo_id ?>"
                                    class="btn btn-sm btn-success">
                                    ✔ Aprovar
                                </a>

                                <a href="<?= BASE_URL ?>/emprestimos/recusar?id=<?= $e['id'] ?>&grupo_id=<?= $grupo_id ?>"
                                    class="btn btn-sm btn-outline-danger">
                                    ✖ Recusar
                                </a>

                            <?php else: ?>

                                <span class="badge bg-warning text-dark">
                                    ⏳ Aguardando aprovação
                                </span>

                            <?php endif; ?>

                        <?php endif; ?>

                        <!-- 💰 PAGAR (somente se aberto ou atrasado) -->
                        <?php if (in_array($e['status'], ['aberto','atrasado'])): ?>
                            <a href="<?= BASE_URL ?>/emprestimos/pagar?id=<?= $e['id'] ?>&grupo_id=<?= $grupo_id ?>"
                                class="btn btn-sm btn-success">
                                ✔
                            </a>
                        <?php endif; ?>

                        <!-- 🗑 Excluir -->
                        <a href="<?= BASE_URL ?>/emprestimos/delete?id=<?= $e['id'] ?>&grupo_id=<?= $grupo_id ?>"
                            class="btn btn-sm btn-danger"
                            onclick="return confirm('Excluir empréstimo?')">
                            🗑
                        </a>

                    </td>

                </tr>

            <?php endforeach; ?>

            </tbody>
        </table>

        <?php endif; ?>

    </div>
</div>