<h3>Solicitar Empréstimo</h3>

<?php if (!empty($erro)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<form method="post">

    <div class="mb-3">
        <label>Valor desejado</label>
        <input type="number" step="0.01" name="valor" class="form-control" required>
    </div>

    <button class="btn btn-primary">Enviar Solicitação</button>
    <a href="<?= base_url("?rota=dashboard@grupo&grupo_id={$grupo_id}") ?>" class="btn btn-secondary">
        Cancelar
    </a>

</form>
