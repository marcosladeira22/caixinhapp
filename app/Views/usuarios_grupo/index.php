<h3>Usuários do Grupo</h3>

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
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>