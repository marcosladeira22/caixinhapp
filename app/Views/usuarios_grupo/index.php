<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Usuários do Grupo</h3>
    <?php require __DIR__ . '/../partials/voltar.php'; ?>
</div>
<hr>
<a href="<?= base_url("?rota=usuarioGrupo@criar&grupo_id={$grupo_id}") ?>" 
   class="btn btn-success mb-3">Adicionar Usuário</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nome</th>
            <th>E-mail</th>
            <th>Nível</th>
            <th>Cotas</th>
            <th>Score</th>
            <th>Ação</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($usuarios as $u): ?>
        <tr>
            <td><?= htmlspecialchars($u['nome']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= $u['nivel'] ?></td>
            <td><?= $u['quantidade_cotas'] ?></td>
            <td><?= $u['score'] ?></td>
            <td>
                <a href="<?= base_url("?rota=usuarioGrupo@editar&id={$u['id']}") ?>">
                    Editar
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>