<h3>Empréstimos Pendentes</h3>

<?php if (empty($emprestimos)): ?>
    <div class="alert alert-info">
        Nenhum empréstimo pendente.
    </div>
<?php endif; ?>

<?php foreach ($emprestimos as $e): ?>
    <div class="card mb-3 p-3 shadow">

        <strong><?= htmlspecialchars($e['nome_usuario']) ?></strong><br>
        Valor solicitado: R$ <?= number_format($e['valor_solicitado'], 2, ',', '.') ?><br>
        Taxa: R$ <?= number_format($e['taxa_aplicada'], 2, ',', '.') ?><br>
        Total a pagar: <strong>R$ <?= number_format($e['valor_total'], 2, ',', '.') ?></strong>

        <div class="mt-3 d-flex gap-2">
            <form method="post" action="<?= base_url('?rota=aprovacaoEmprestimo@aprovar') ?>">
                <input type="hidden" name="emprestimo_id" value="<?= $e['id'] ?>">
                <input type="hidden" name="grupo_id" value="<?= $grupo_id ?>">
                <button class="btn btn-success btn-sm">Aprovar</button>
            </form>

            <form method="post" action="<?= base_url('?rota=aprovacaoEmprestimo@rejeitar') ?>">
                <input type="hidden" name="emprestimo_id" value="<?= $e['id'] ?>">
                <input type="hidden" name="grupo_id" value="<?= $grupo_id ?>">
                <button class="btn btn-danger btn-sm">Rejeitar</button>
            </form>
        </div>
    </div>
<?php endforeach; ?>
