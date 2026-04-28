<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Usuários do Grupo</h3>
    <?php require __DIR__ . '/../partials/voltar.php'; ?>
</div>
<hr>
<a href="<?= base_url("?rota=usuarioGrupo@criar&grupo_id={$grupo_id}") ?>" 
   class="btn btn-success mb-3">Adicionar Usuário</a>

<form method="get" class="mb-3">
    <input type="hidden" name="rota" value="usuarioGrupo@index">
    <input type="hidden" name="grupo_id" value="<?= (int)$grupo_id ?>">
    <input type="hidden" name="page" value="1">

    <label class="me-2">Resultados por página:</label>
    <select name="per_page" onchange="this.form.submit()">
        <?php foreach ([5, 10, 20, 50] as $n): ?>
            <option value="<?= $n ?>" <?= $paginator->porPagina == $n ? 'selected' : '' ?>>
                <?= $n ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>
<table class="table table-bordered">
    <thead class="table-light text-center">
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
            <td class="text-center"><?= (int)$u['quantidade_cotas'] ?></td>
            <td class="text-center"><?= htmlspecialchars($u['nivel']) ?></td>
            <td class="text-center">
                <?= $u['ativo'] ? 'Ativo' : 'Inativo' ?>
            </td>
            <td class="text-center">
                <a class="btn btn-sm btn-primary"
                href="<?= base_url('?rota=usuarioGrupo@editar') . '&id=' . (int)$u['grupo_usuario_id'] ?>">
                    Editar
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php require __DIR__.'/../partials/pagination.php'; ?>