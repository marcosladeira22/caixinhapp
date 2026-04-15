<div class="container mt-4">

    <h4>💸 Empréstimos</h4>
    <hr>

    <a href="<?= BASE_URL ?>/emprestimos/create?grupo_id=<?= $grupo_id ?>" 
       class="btn btn-primary mb-3">
        + Novo Empréstimo
    </a>

    <table class="table">
        <thead>
            <tr>
                <th>Usuário</th>
                <th>Valor</th>
                <th>Com Juros</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>

        <?php foreach($emprestimos as $e): ?>

            <tr>
                <td><?= $e['nome'] ?></td>
                <td>R$ <?= number_format($e['valor'], 2, ',', '.') ?></td>
                <td>R$ <?= number_format($e['valor_com_juros'], 2, ',', '.') ?></td>
                <td><?= $e['status'] ?></td>
            </tr>

        <?php endforeach; ?>

        </tbody>
    </table>

</div>