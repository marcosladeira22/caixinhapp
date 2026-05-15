<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold m-0">Logs do sistema</h4>
        <?php require __DIR__ . '/../partials/voltar.php'; ?>
    </div>

    <?php require __DIR__ . '/../components/alert.php'; ?>

    <!-- ✅ FILTRO -->
    <div class="card shadow mb-3">
        <div class="card-body">

            <form method="get" class="row g-2 align-items-center">

                <input type="hidden" name="rota" value="log@index">
                <input type="hidden" name="grupo_id" value="<?= isset($grupo_id) ? (int)$grupo_id : 0 ?>">
                <input type="hidden" name="page" value="1">

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Filtrar por ação</label>
                    <select name="acao" class="form-select">
                        <option value="">Todas as ações</option>
                        <option value="PAGAMENTO" <?= ($acao ?? '') === 'PAGAMENTO' ? 'selected' : '' ?>>Pagamento</option>
                        <option value="EMPRESTIMO" <?= ($acao ?? '') === 'EMPRESTIMO' ? 'selected' : '' ?>>Empréstimo</option>
                        <option value="USUARIO_GRUPO" <?= ($acao ?? '') === 'USUARIO_GRUPO' ? 'selected' : '' ?>>Usuário no grupo</option>
                        <option value="PERFIL" <?= ($acao ?? '') === 'PERFIL' ? 'selected' : '' ?>>Perfil</option>
                        <option value="FECHAMENTO" <?= ($acao ?? '') === 'FECHAMENTO' ? 'selected' : '' ?>>Fechamento</option>
                    </select>
                </div>

                <div class="col-md-2 mt-3">
                    <button class="btn btn-primary w-100">Filtrar</button>
                </div>

            </form>

        </div>
    </div>

    <?php if (!empty($logs)): ?>

        <!-- ✅ TABELA -->
        <div class="card shadow">
            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-striped table-bordered align-middle">

                        <thead class="table-light text-center">
                            <tr>
                                <th>Data</th>
                                <th>Usuário</th>
                                <th>Ação</th>
                                <th>Descrição</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($logs as $log): ?>

                                <tr>

                                    <td class="text-center">
                                        <?= date('d/m/Y H:i', strtotime($log['criado_em'])) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($log['nome_usuario'] ?? 'Sistema') ?>
                                    </td>

                                    <td class="text-center">
                                        <?php
                                        $cor = match($log['acao']) {
                                            'PAGAMENTO' => 'success',
                                            'EMPRESTIMO' => 'primary',
                                            'USUARIO_GRUPO' => 'info',
                                            'PERFIL' => 'secondary',
                                            'FECHAMENTO' => 'danger',
                                            default => 'dark'
                                        };
                                        ?>
                                        <span class="badge bg-<?= $cor ?>">
                                            <?= htmlspecialchars($log['acao']) ?>
                                        </span>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($log['descricao']) ?>
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
            <h5 class="text-muted">Nenhum log encontrado</h5>
            <p class="text-muted mb-0">Não há registros para o filtro selecionado.</p>
        </div>

    <?php endif; ?>

    <!-- ✅ PAGINAÇÃO -->
    <?php
    $rota = 'log@index';
    $extras = [
        'acao' => $acao ?? null
    ];
    require __DIR__ . '/../partials/paginator.php';
    ?>

</div>