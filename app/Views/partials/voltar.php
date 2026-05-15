<?php
$voltar = $_SERVER['HTTP_REFERER'] ?? base_url('?rota=dashboard@index');
?>

<a href="<?= htmlspecialchars($voltar) ?>" class="btn btn-outline-secondary mb-3">
    ← Voltar
</a>
