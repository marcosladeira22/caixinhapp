
<h3>Selecione um Grupo</h3>

<ul class="list-group mt-3">
    <?php foreach ($grupos as $grupo): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <?= htmlspecialchars($grupo['nome']) ?>

            <a href="<?= base_url("?rota=dashboard@grupo&grupo_id={$grupo['id']}") ?>"class="btn btn-primary btn-sm">
                Entrar
            </a>
        </li>
    <?php endforeach; ?>
</ul>