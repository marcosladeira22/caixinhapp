<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold m-0">Usuários do grupo</h4>
        <?php require __DIR__ . '/../partials/voltar.php'; ?>
    </div>

    <?php require __DIR__ . '/../components/alert.php'; ?>

    <!-- BOTÃO ADICIONAR -->
    <div class="mb-3">

        <a href="<?= base_url('?rota=usuarioGrupo@criar&grupo_id=' . (int)$grupo_id) ?>" 
           class="btn btn-success">
            + Adicionar usuário
        </a>

    </div>

    <?php if (!empty($usuarios)): ?>

        <div class="card shadow">
            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-striped table-bordered align-middle">

                        <thead class="table-light text-center">
                            <tr>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Nível</th>
                                <th>Cotas</th>
                                <th>Status</th>
                                <th style="width: 120px;">Ação</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($usuarios as $u): ?>

                                <tr>
                                    <td><?= htmlspecialchars($u['nome']) ?></td>

                                    <td><?= htmlspecialchars($u['email']) ?></td>

                                    <td class="text-center">
                                        <?php if ($u['nivel'] === 'ADMIN'): ?>
                                            <span class="badge bg-dark">Admin</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Membro</span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center">
                                        <?= (int)$u['quantidade_cotas'] ?>
                                    </td>

                                    <td class="text-center">
                                        <?php if (!empty($u['ativo'])): ?>
                                            <span class="badge bg-success">Ativo</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Inativo</span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center">

                                        <a 
                                            href="<?= base_url('?rota=usuarioGrupo@editar&id=' . (int)$u['grupo_usuario_id']) ?>" 
                                            class="btn btn-sm btn-primary"
                                        >
                                            Editar
                                        </a>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            </div>
        </div>

    <?php else: ?>

        <!-- EMPTY STATE -->
        <div class="text-center p-5 bg-white shadow rounded">
            <h5 class="text-muted">Nenhum usuário no grupo</h5>

            <a href="<?= base_url('?rota=usuarioGrupo@criar&grupo_id=' . (int)$grupo_id) ?>" 
               class="btn btn-success mt-3">
                Adicionar primeiro usuário
            </a>
        </div>

    <?php endif; ?>

    <!-- PAGINAÇÃO -->
    <?php
    $rota = 'usuarioGrupo@index';
    $extras = [];
    require __DIR__ . '/../partials/paginator.php';
    ?>

</div>