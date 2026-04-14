<div class="container mt-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h3><?= $grupo['nome'] ?></h3>
            <p class="text-muted mb-1"><?= $grupo['descricao'] ?></p>
        </div>

        <a href="<?= BASE_URL ?>/dashboard" class="btn btn-outline-secondary">
            ← Voltar
        </a>
    </div>

    <!-- INFO DO GRUPO -->
    <div class="card p-3 mt-3">
        <strong>💰 Valor da cota:</strong>
        R$ <?= number_format($grupo['valor_cota'], 2, ',', '.') ?>
    </div>

    <!-- FILTRO -->
    <form method="GET" class="mt-3">
        <div class="row justify-content-end align-items-end">
            <div class="col-md-4">
                <input type="month" name="mes" class="form-control"
                       value="<?= date('Y-m', strtotime($mesAtual)) ?>">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100">🔍 Filtrar</button>
            </div>
        </div>
    </form>

    <!-- DASHBOARD -->
    <div class="row mt-3">
        <div class="col-md-4">
            <div class="card p-3 text-center bg-success text-white">
                <h6>💵 Arrecadado </h6>
                <h4>R$ <?= number_format($totalArrecadado, 2, ',', '.') ?></h4>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3 text-center bg-danger text-white">
                <h6>⏳ Pendente</h6>
                <h4>R$ <?= number_format($totalPendente, 2, ',', '.') ?></h4>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3 text-center bg-primary text-white">
                <h6>📊 % Pago</h6>
                <h4><?= number_format($percentualPago, 2, ',', '.') ?>%</h4>
            </div>
        </div>
    </div>

    <!-- PAGAMENTOS -->
    <form method="POST" action="<?= BASE_URL ?>/grupos/pagamentos/salvar">
        <input type="hidden" name="grupo_id" value="<?= $grupo['id'] ?>">
        <input type="hidden" name="mes" value="<?= date('Y-m', strtotime($mesAtual)) ?>">

        <div class="card mt-4 p-3">
            <h5>💳 Pagamentos</h5>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Usuário</th>
                            <th>Status</th>
                            <th>Valor Pago</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($membros as $membro): ?>

                            <?php
                                $status = $pagamentosMap[$membro['id']]['status'] ?? 'pendente';
                                $valor  = $pagamentosMap[$membro['id']]['valor_pago'] ?? 0;
                            ?>

                            <tr>
                                <td>
                                    <strong><?= $membro['nome'] ?></strong><br>
                                    <small><?= $membro['email'] ?></small>
                                </td>

                                <td>
                                    <select name="pagamentos[<?= $membro['id'] ?>]" class="form-select">
                                        <option value="pendente" <?= $status === 'pendente' ? 'selected' : '' ?>>Pendente</option>
                                        <option value="pago" <?= $status === 'pago' ? 'selected' : '' ?>>Pago</option>
                                    </select>
                                </td>

                                <td>
                                    R$ <?= number_format($valor, 2, ',', '.') ?>
                                </td>
                            </tr>

                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <button class="btn btn-primary mt-3">💸 Salvar Pagamentos</button>
        </div>
    </form>

    <!-- COTAS -->
    <form method="POST" action="<?= BASE_URL ?>/grupos/cotas/salvar">
        <input type="hidden" name="grupo_id" value="<?= $grupo['id'] ?>">

        <div class="card mt-4 p-3">
            <h5>💰 Cotas</h5>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Usuário</th>
                            <th class="text-center">Cotas</th>
                            <th class="text-center">Total</th>
                            <th class="text-center">Convite</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php $totalGeral = 0; ?>

                        <?php foreach($membros as $membro): ?>

                            <?php
                                $qtd = $cotasMap[$membro['id']] ?? 0;
                                $total = $qtd * $grupo['valor_cota'];
                                $totalGeral += $total;
                            ?>

                            <tr>
                                <td>
                                    <strong><?= $membro['nome'] ?></strong><br>
                                    <small><?= $membro['email'] ?></small>
                                </td>

                                <td class="text-center">
                                    <input type="number"
                                           name="cotas[<?= $membro['id'] ?>]"
                                           class="form-control text-center"
                                           value="<?= $qtd ?>"
                                           min="0">
                                </td>

                                <td class="text-center">
                                    <strong>R$ <?= number_format($total, 2, ',', '.') ?></strong>
                                </td>

                                <td class="text-center">

                                    <?php if (($membro['convite_status'] ?? '') === 'aceito'): ?>
                                        <span class="badge bg-success">Ativo</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Pendente</span>
                                        <br>
                                        <a href="<?= BASE_URL ?>/usuarios/reenviar-convite?usuario_id=<?= $membro['id'] ?>"
                                           class="btn btn-sm btn-outline-primary mt-1">
                                            Reenviar
                                        </a>
                                    <?php endif; ?>

                                </td>
                            </tr>

                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>

            <div class="alert alert-info mt-3">
                💰 Total do grupo:
                <strong>R$ <?= number_format($totalGeral, 2, ',', '.') ?></strong>
            </div>

            <button class="btn btn-success">Salvar Cotas</button>
        </div>
    </form>

    <!-- ADICIONAR MEMBRO -->
    <div class="card p-3 mt-4">
        <h5>➕ Adicionar Membro</h5>

        <form method="POST" action="<?= BASE_URL ?>/grupos/adicionar-membro">

            <input type="hidden" name="grupo_id" value="<?= $grupo['id'] ?>">

            <div class="row">

                <div class="col-md-3 text-center">
                    <label for="" class="form-label"><strong>Nome</strong></label>
                    <input type="text" name="nome" class="form-control" placeholder="Nome" required>
                </div>

                <div class="col-md-3 text-center">
                    <label for="" class="form-label"><strong>E-mail</strong></label>
                    <input type="email" name="email" class="form-control" placeholder="E-mail" required>
                </div>

                <div class="col-md-2 text-center">
                    <label for="" class="form-label"><strong>Nível</strong></label>
                    <select name="nivel" class="form-select">
                        <option value="membro">Membro</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div class="col-md-1 text-center">
                    <label for="" class="form-label"><strong>Cotas</strong></label>
                    <input type="number" name="cotas" class="form-control" value="1" min="1">
                </div>

                <div class="col-md-3 text-center">
                    <label for="" class="form-label"><strong>Convite</strong></label>
                    <select name="enviar_convite" class="form-select">
                        <option value="0">Sem convite</option>
                        <option value="1">Enviar convite</option>
                    </select>
                </div>

            </div>

            <button class="btn btn-success mt-3">Salvar</button>

        </form>
    </div>

</div>