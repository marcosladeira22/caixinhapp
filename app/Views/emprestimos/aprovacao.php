<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold m-0">Empréstimos pendentes</h4>
        <?php require __DIR__ . '/../partials/voltar.php'; ?>
    </div>

    <?php require __DIR__ . '/../components/alert.php'; ?>

    <?php if (!empty($emprestimos)): ?>

        <div class="row g-3">

            <?php foreach ($emprestimos as $e): ?>

                <div class="col-md-6">

                    <div class="card shadow h-100">
                        <div class="card-body">

                            <h6 class="fw-bold mb-2">
                                <?= htmlspecialchars($e['nome_usuario']) ?>
                            </h6>

                            <p class="mb-1 text-muted small">
                                Valor solicitado:
                                <strong>R$ <?= number_format($e['valor_solicitado'], 2, ',', '.') ?></strong>
                            </p>

                            <p class="mb-1 text-muted small">
                                Taxa:
                                R$ <?= number_format($e['taxa_aplicada'], 2, ',', '.') ?>
                            </p>

                            <p class="mb-3">
                                Total a pagar:
                                <strong class="text-primary">
                                    R$ <?= number_format($e['valor_total'], 2, ',', '.') ?>
                                </strong>
                            </p>

                            <div class="d-flex gap-2">

                                <!-- ✅ APROVAR -->
                                <form 
                                    method="post" 
                                    action="<?= base_url('?rota=aprovacaoEmprestimo@aprovar') ?>"
                                    class="form-acao flex-grow-1"
                                >
                                    <input type="hidden" name="_token" value="<?= \Core\Csrf::gerarToken() ?>">
                                    <input type="hidden" name="emprestimo_id" value="<?= (int)$e['id'] ?>">
                                    <input type="hidden" name="grupo_id" value="<?= (int)$grupo_id ?>">

                                    <button class="btn btn-success w-100">
                                        Aprovar
                                    </button>
                                </form>

                                <!-- ✅ REJEITAR -->
                                <form 
                                    method="post" 
                                    action="<?= base_url('?rota=aprovacaoEmprestimo@rejeitar') ?>"
                                    class="form-acao flex-grow-1"
                                >
                                    <input type="hidden" name="_token" value="<?= \Core\Csrf::gerarToken() ?>">
                                    <input type="hidden" name="emprestimo_id" value="<?= (int)$e['id'] ?>">
                                    <input type="hidden" name="grupo_id" value="<?= (int)$grupo_id ?>">

                                    <button class="btn btn-outline-danger w-100">
                                        Rejeitar
                                    </button>
                                </form>

                            </div>

                        </div>
                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    <?php else: ?>

        <!-- ✅ EMPTY STATE -->
        <div class="text-center p-5 bg-white shadow rounded">
            <h5 class="text-muted">Nenhum empréstimo pendente</h5>
            <p class="text-muted">Não há solicitações aguardando aprovação.</p>
        </div>

    <?php endif; ?>

</div>

<!-- ✅ FASE 5.4 — PREVENÇÃO DUPLO CLIQUE -->
<script>
document.querySelectorAll('.form-acao').forEach(form => {
    form.addEventListener('submit', function () {
        const btn = this.querySelector('button');
        btn.disabled = true;

        if (btn.innerText.includes('Aprovar')) {
            btn.innerText = 'Aprovando...';
        } else {
            btn.innerText = 'Rejeitando...';
        }
    });
});
</script>