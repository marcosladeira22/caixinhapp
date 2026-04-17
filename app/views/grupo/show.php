<?php $aba = $_GET['aba'] ?? 'geral'; ?>

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
    <hr>
    <!-- INFO -->
    <div class="card p-3 mt-3">
        <strong>💰 Valor da cota:</strong>
        R$ <?= number_format($grupo['valor_cota'], 2, ',', '.') ?>
    </div>

    <!-- MENU ABAS -->
    <div class="mt-3 mb-3">
        <div class="btn-group w-100 flex-wrap">

            <a href="<?= BASE_URL ?>/grupos/<?= $grupo['id'] ?>"
               class="btn <?= $aba === 'geral' ? 'btn-primary' : 'btn-outline-primary' ?>">
                📊 Geral
            </a>

            <a href="<?= BASE_URL ?>/grupos/<?= $grupo['id'] ?>?aba=cotas"
               class="btn <?= $aba === 'cotas' ? 'btn-success' : 'btn-outline-success' ?>">
                💰 Cotas
            </a>

            <a href="<?= BASE_URL ?>/grupos/<?= $grupo['id'] ?>?aba=emprestimos"
            class="btn <?= $aba === 'emprestimos' ? 'btn-warning' : 'btn-outline-warning' ?>">
                💸 Empréstimos
            </a>

            <a href="<?= BASE_URL ?>/grupos/<?= $grupo['id'] ?>?aba=regras"
               class="btn <?= $aba === 'regras' ? 'btn-dark' : 'btn-outline-dark' ?>">
                ⚙️ Regras
            </a>

        </div>
    </div>

    <!-- FILTRO (SÓ NA GERAL) -->
    <?php if ($aba === 'geral'): ?>
        <form method="GET" class="mt-3">
            <div class="row">
                <div class="col-md-4">
                    <input type="month" name="mes" class="form-control"
                           value="<?= date('Y-m', strtotime($mesAtual)) ?>">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100">Filtrar</button>
                </div>
            </div>
        </form>
    <?php endif; ?>

    <!-- ========================= -->
    <!-- 📊 ABA GERAL -->
    <!-- ========================= -->
    <?php if ($aba === 'geral'): ?>

        <!-- DASHBOARD -->
        <div class="row mt-3">

            <div class="col-md-4">
                <div class="card p-3 text-center bg-success text-white">
                    <h6>💵 Arrecadado</h6>
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
        <br>

        <!-- EMPRÉSTIMOS NO DASH -->
        <div class="d-flex align-items-center my-4">
            <div class="flex-grow-1 border-bottom"></div>
            <span class="mx-3 fw-bold text-secondary">Empréstimos</span>
            <div class="flex-grow-1 border-bottom"></div>
        </div>
        <div class="row mt-3">
            <div class="col-md-3">
                <div class="card p-3 text-center bg-warning">
                    <h6>💸 Emprestado</h6>
                    <h4>R$ <?= number_format($totalEmprestado, 2, ',', '.') ?></h4>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-3 text-center bg-info text-white">
                    <h6>💰 Recebido</h6>
                    <h4>R$ <?= number_format($totalRecebidoEmprestimos, 2, ',', '.') ?></h4>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-3 text-center bg-dark text-white">
                    <h6>📊 Saldo Real</h6>
                    <h4>R$ <?= number_format($saldoReal, 2, ',', '.') ?></h4>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-3 text-center bg-info-subtle">
                    <h6>📈 Lucro com Juros</h6>
                    <h4>R$ <?= number_format($lucroJuros ?? 0, 2, ',', '.') ?></h4>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center my-4">
            <div class="flex-grow-1 border-bottom"></div>
        </div>
        <div class="mt-3">
            <?php if ($totalAtrasados > 0): ?>
                <div class="alert alert-danger">
                    <strong>⚠️ Inadimplência detectada</strong><br>
                    📄 Empréstimos atrasados: 
                    <strong><?= $totalAtrasados ?></strong><br>
                    💸 Valor em risco: 
                    <strong>R$ <?= number_format($valorAtrasado, 2, ',', '.') ?></strong><br>
                    📊 Percentual:
                    <strong><?= number_format($percentualInadimplencia, 1, ',', '.') ?>%</strong>
                </div>
            <?php else: ?>
                <div class="alert alert-success">
                    ✅ Nenhum empréstimo em atraso
                </div>
            <?php endif; ?>
        </div>

        <!-- PAGAMENTOS -->
        <form method="POST" action="<?= BASE_URL ?>/grupos/pagamentos/salvar">
            <input type="hidden" name="grupo_id" value="<?= $grupo['id'] ?>">
            <input type="hidden" name="mes" value="<?= date('Y-m', strtotime($mesAtual)) ?>">

            <div class="card mt-4 p-3">
                <h5>💳 Pagamentos <small class="text-muted" style="font-size: 0.8em;">(Cotas)</small></h5>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Usuário</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Pago</th>
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

                                    <td class="text-center">
                                        <select name="pagamentos[<?= $membro['id'] ?>]" class="form-select">
                                            <option value="pendente" <?= $status === 'pendente' ? 'selected' : '' ?>>Pendente</option>
                                            <option value="pago" <?= $status === 'pago' ? 'selected' : '' ?>>Pago</option>
                                        </select>
                                    </td>

                                    <td class="text-center">
                                        R$ <?= number_format($valor, 2, ',', '.') ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <button class="btn btn-primary mt-3">Salvar Pagamentos</button>
            </div>
        </form>

    <?php endif; ?>


    <!-- ========================= -->
    <!-- 💰 ABA COTAS -->
    <!-- ========================= -->
    <?php if ($aba === 'cotas'): ?>

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
                                <th class="text-center">Status</th>
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
                                               value="<?= $qtd ?>">
                                    </td>

                                    <td class="text-center">
                                        R$ <?= number_format($total, 2, ',', '.') ?>
                                    </td>

                                    <td class="text-center">
                                        <?php if (($membro['convite_status'] ?? '') === 'aceito'): ?>
                                            <span class="badge bg-success">Ativo</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Pendente</span>
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

    <?php endif; ?>


    <!-- ========================= -->
    <!-- 💸 ABA EMPRÉSTIMOS -->
    <!-- ========================= -->
    <?php if ($aba === 'emprestimos'): ?>
        <div class="card mt-4 p-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h5>💸 Empréstimos <small class="text-muted" style="font-size: 0.8em;">(Últimos 5)</small></h5>
                </div>
                <?php if (empty($emprestimos)): ?>    
                    <div>
                        <a href="<?= BASE_URL ?>/emprestimos/create?grupo_id=<?= $grupo['id'] ?>" 
                            class="btn btn-primary mb-3">
                            + Novo Empréstimo
                        </a>
                    </div>
                <?php else: ?>
                    <div>
                        <a href="<?= BASE_URL ?>/emprestimos/create?grupo_id=<?= $grupo['id'] ?>" 
                            class="btn btn-outline-primary mb-3">
                            ➕
                        </a>
                        <a href="<?= BASE_URL ?>/emprestimos?grupo_id=<?= $grupo['id'] ?>" 
                            class="btn btn-primary mb-3">
                            Ver totdos
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if (empty($emprestimos)): ?>
                <p>Nenhum empréstimo</p>
            <?php else: ?>

                <table class="table table-bordered align-middle">
                    <thead class="table-light text-center">
                        <tr>
                            <th>Usuário</th>
                            <th>Valor</th>
                            <th>Com Juros</th>
                            <th>Status</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach($emprestimos as $e): ?>
                            <tr>
                                <td><?= $e['nome'] ?></td>
                                <td class="text-center">R$ <?= number_format($e['valor'], 2, ',', '.') ?></td>
                                <td class="text-center">R$ <?= number_format($e['valor_com_juros'], 2, ',', '.') ?></td>
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
                                    <a href="<?= BASE_URL ?>/emprestimos/edit?id=<?= $e['id'] ?>&grupo_id=<?= $grupo['id'] ?>"
                                            class="btn btn-sm btn-warning">✏️</a>

                                    <a href="<?= BASE_URL ?>/emprestimos/pagar?id=<?= $e['id'] ?>&grupo_id=<?= $grupo['id'] ?>"
                                            class="btn btn-sm btn-success">✔</a>

                                    <a href="<?= BASE_URL ?>/emprestimos/delete?id=<?= $e['id'] ?>&grupo_id=<?= $grupo['id'] ?>"
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
    <?php endif; ?>


    <!-- ========================= -->
    <!-- ⚙️ ABA REGRAS -->
    <!-- ========================= -->
    <?php if ($aba === 'regras'): ?>
        <div class="card mt-4 p-3">
            <h5>⚙️ Regras</h5>
            <a href="<?= BASE_URL ?>/regras?grupo_id=<?= $grupo['id'] ?>" class="btn btn-primary mb-3">
                ➕ Nova Regra
            </a>
            <?php if (!$regras): ?>
                <p>Nenhuma regra cadastrada</p>
            <?php else: ?>
                <ul class="list-group">
                    <li class="list-group-item">
                        Mínimo: R$ <?= number_format($regras['valor_minimo'], 2, ',', '.') ?>
                    </li>
                    <li class="list-group-item">
                        Máximo: R$ <?= number_format($regras['valor_maximo'], 2, ',', '.') ?>
                    </li>
                    <li class="list-group-item">
                        Juros inicial:
                        <?= $regras['juros_inicial_tipo'] ?> -
                        <?= $regras['juros_inicial_valor'] ?>
                    </li>
                </ul>
            <?php endif; ?>
        </div>
    <?php endif; ?>


    <!-- ========================= -->
    <!-- 👥 ADICIONAR MEMBRO -->
    <!-- ========================= -->
    <div class="card p-3 mt-4">
        <h5>➕ Adicionar Membro</h5>
        <hr>
        <form method="POST" action="<?= BASE_URL ?>/grupos/adicionar-membro">
            <input type="hidden" name="grupo_id" value="<?= $grupo['id'] ?>">
            <div class="row text-center">
                <div class="col-md-3">
                    <label for="" class="form-label fw-bold">Nome</label>
                    <input type="text" name="nome" class="form-control" placeholder="Nome" required>
                </div>
                <div class="col-md-3">
                    <label for="" class="form-label fw-bold">E-mail</label>
                    <input type="email" name="email" class="form-control" placeholder="E-mail" required>
                </div>
                <div class="col-md-2">
                    <label for="" class="form-label fw-bold">Nível</label>
                    <select name="nivel" class="form-select">
                        <option value="membro">Membro</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <label for="" class="form-label fw-bold">Cotas</label>
                    <input type="number" name="cotas" class="form-control text-center" value="1">
                </div>
                <div class="col-md-3">
                    <label for="" class="form-label fw-bold">Convite</label>
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