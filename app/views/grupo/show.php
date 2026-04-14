<div class="container mt-4">
    <h3><?= $grupo['nome'] ?></h3>
    <p class="text-muted"><?= $grupo['descricao'] ?></p>
    <div class="card p-3 mt-3">
        <h5>Informações do Grupo</h5>
        <p>
            💰 Valor da cota:
            <strong>R$ <?= number_format($grupo['valor_cota'], 2, ',', '.') ?></strong>
        </p>
        <a href="<?= BASE_URL ?>/dashboard" class="btn btn-secondary mt-3">Voltar</a>
    </div>
</div>
<hr>
<div class="card p-3 mt-4">
    <h5>👥 Criar e Adicionar Membro</h5>
    <form method="POST" action="<?= BASE_URL ?>/grupos/adicionar-membro">
        <input type="hidden" name="grupo_id" value="<?= $grupo['id'] ?>">
        <div class="row">
            <!-- NOME -->
            <div class="col-md-3">
                <label for="">Nome</label>
                <input type="text" name="nome" class="form-control" placeholder="Nome" required>
            </div>
            <!-- EMAIL -->
            <div class="col-md-3">
                <label for="">E-mail</label>
                <input type="email" name="email" class="form-control" placeholder="E-mail" required>
            </div>
            <!-- NÍVEL -->
            <div class="col-md-2">
                <label for="">Nível</label>
                <select name="nivel" class="form-control">
                    <option value="membro">Membro</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>
            <!-- COTAS -->
            <div class="col-md-2">
                <label for="">Cotas</label>
                <input type="number" name="cotas" class="form-control" value="1" min="1" placeholder="Cotas">
            </div>
            <!-- ENVIAR CONVITE -->
            <div class="col-md-2">
                <label for="">Convite @</label>
                <select name="enviar_convite" class="form-control">
                    <option value="0">Criar só</option>
                    <option value="1">Criar e convidar</option>
                </select>
            </div>
        </div>
        <button class="btn btn-success mt-2">Salvar</button>
    </form>
</div>
<hr>
<!-- FILTRO POR MÊS -->
<form method="GET" action="" class="mb-3">
    <div class="row">
        <div class="col-md-4">
            <input 
                type="month" 
                name="mes" 
                class="form-control"
                value="<?= date('Y-m', strtotime($mesAtual)) ?>">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary">
                Filtrar
            </button>
        </div>
    </div>
</form>

<!-- DASHBOARD FINANCEIRO -->
<div class="row mt-3">
    <!-- TOTAL ARRECADADO -->
    <div class="col-md-4">
        <div class="card p-3 text-center bg-success text-white">
            <h6>💰 Arrecadado</h6>
            <h4>
                R$ <?= number_format($totalArrecadado, 2, ',', '.') ?>
            </h4>
        </div>
    </div>

    <!-- TOTAL PENDENTE -->
    <div class="col-md-4">
        <div class="card p-3 text-center bg-danger text-white">
            <h6>⏳ Pendente</h6>
            <h4>
                R$ <?= number_format($totalPendente, 2, ',', '.') ?>
            </h4>
        </div>
    </div>

    <!-- PERCENTUAL -->
    <div class="col-md-4">
        <div class="card p-3 text-center bg-primary text-white">
            <h6>📊 % Pago</h6>
            <h4>
                <?= number_format($percentualPago, 2, ',', '.') ?>%
            </h4>
        </div>
    </div>
</div>
<form method="POST" action="<?= BASE_URL ?>/grupos/pagamentos/salvar">
    <input type="hidden" name="grupo_id" value="<?= $grupo['id'] ?>">
    <input type="hidden" name="mes" value="<?= date('Y-m', strtotime($mesAtual)) ?>">
    <div class="card mt-4 p-3">
        <h5>👥 Membros do Grupo</h5>
        <?php if(empty($membros)): ?>
            <p class="text-muted">Nenhum membro encontrado..</p>
        <?php else: ?>
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Nível</th>
                        <th>Status</th>
                        <th>Pago (R$)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($membros as $membro): ?>
                        <tr>
                            <td><?= $membro['nome'] ?></td>
                            <td><?= $membro['email'] ?></td>
                            <td><?= $membro['nivel'] === 'admin' ? 'Aministrador' : 'Membro' ?></td>
                            <td>
                                <?php 
                                    // Define status do pagamento (ou padrão pendente)
                                    $status = $pagamentosMap[$membro['id']]['status'] ?? 'pendente';
                                ?>
                                <select name="pagamentos[<?= $membro['id'] ?>]" class="form-control">
                                    <option value="pendente" <?= ($status === 'pendente') ? 'selected' : '' ?>>Pendente</option>
                                    <option value="pago" <?= ($status === 'pago') ? 'selected' : '' ?>>Pago</option>
                                </select>
                            </td>
                            <td>
                                <?php
                                    $qtd = $cotasMap[$membro['id']] ?? 0;
                                    $valor = $pagamentosMap[$membro['id']]['valor_pago'] ?? 0;

                                    echo "R$ " . number_format($valor, 2, ',', '.');
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        <button class="btn btn-primary mt-3">Salvar Pagamentos</button>
    </div>
</form>
<form method="POST" action="<?= BASE_URL ?>/grupos/cotas/salvar">
    <input type="hidden" name="grupo_id" value="<?= $grupo['id'] ?>">
    <div class="card mt-4 p-3">
        <h5>👥 Membros | 💰 Cotas</h5>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Nível</th>
                    <th>Cotas</th>
                    <th>Valor Cota</th>
                    <th>Total (R$)</th>
                    <th>Convite</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Organiza cotas por usuário_id
                    $cotasMap = [];
                    foreach ($cotasMap as $c) {
                        $cotasMap[$c['usuario_id']] = $c['quantidade'];
                    }
                ?>
                <?php foreach($membros as $membro): ?>
                    <tr>
                        <td><?= $membro['nome'] ?></td>
                        <td><?= $membro['email'] ?></td>
                        <td>
                            <?= $membro['nivel'] === 'admin' ? 'Administrador' : 'Membro' ?>
                        </td>
                        <td style="width: 120px;">
                            <!-- INPUT DE COTAS -->
                            <input  
                                type="number" 
                                name="cotas[<?= $membro['id'] ?>]" 
                                class="form-control"
                                min="0"
                                value="<?= $cotasMap[$membro['id']] ?? 0 ?>"
                            >
                        </td>
                        <td>
                            R$ <?= number_format($grupo['valor_cota'], 2, ',', '.') ?>
                        </td>
                        <td>
                            <?php 
                                $qtd = $cotasMap[$membro['id']] ?? 0;
                                $total = $qtd * $grupo['valor_cota'];
                                echo "R$ " . number_format($total, 2, ',', '.');
                            ?>
                        </td>
                        <td>
                            <?php if (($membro['convite_status'] ?? '') === 'aceito'): ?>

                                <span class="badge bg-success">Ativo</span>

                            <?php elseif (($membro['convite_status'] ?? '') === 'pendente'): ?>

                                <span class="badge bg-warning text-dark">Pendente</span><br>
                                <a href="<?= BASE_URL ?>/convite?token=<?= $membro['convite_token'] ?>" target="_blank">
                                    Link Convite
                                </a>

                            <?php else: ?>

                                <span class="badge bg-secondary">Sem status</span>

                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php
            $totalGeral = 0;
            foreach ($cotasMap as $qtd) {
                $totalGeral += $qtd * $grupo['valor_cota'];
            }
        ?>
        <div class="alert alert-info mt-3">
            💰 <strong>Total geral do grupo:</strong> 
            R$ <?= number_format($totalGeral, 2, ',', '.') ?>
        </div>
        <!-- BOTÃO SALVAR -->
        <button class="btn btn-success">
            Salvar Cotas
        </button>
    </div>
</form>