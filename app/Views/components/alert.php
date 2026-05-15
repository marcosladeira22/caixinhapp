<?php
$erro      = \Core\Sessao::getFlash('erro') ?? ($erro ?? null);
$sucesso   = \Core\Sessao::getFlash('sucesso') ?? ($sucesso ?? null);
$aviso     = \Core\Sessao::getFlash('aviso') ?? ($aviso ?? null);
?>

<?php if (!empty($erro)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<?php if (!empty($sucesso)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($sucesso) ?></div>
<?php endif; ?>

<?php if (!empty($aviso)): ?>
    <div class="alert alert-warning"><?= htmlspecialchars($aviso) ?></div>
<?php endif; ?>