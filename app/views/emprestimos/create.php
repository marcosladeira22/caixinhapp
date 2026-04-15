<div class="container mt-4">
    <h4>💸 Novo Empréstimo</h4>
    <hr>
    <form method="POST" action="<?= BASE_URL ?>/emprestimos/store">
        <input type="hidden" name="grupo_id" value="<?= $grupo_id ?>">
        <!-- USUÁRIO -->
        <div class="mb-3">
            <label class="form-label fw-bold">Usuário</label>
            <select name="usuario_id" class="form-select" required>
                <option value="">Selecione</option>
                <?php foreach($membros as $m): ?>
                    <option value="<?= $m['id'] ?>">
                        <?= $m['nome'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <!-- VALOR -->
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Valor</label>
                <input type="number" step="0.01" name="valor" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Valor com juros (estimado)</label>
                <input type="text" id="preview" class="form-control" disabled>
            </div>
        </div>
        <div class="row">

            <!-- DATA EMPRESTIMO -->
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Data do empréstimo</label>
                <input type="date" name="data_emprestimo" class="form-control" required>
            </div>

            <!-- VENCIMENTO -->
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Data de vencimento</label>
                <input type="date" name="data_vencimento" class="form-control" required>
            </div>
        </div>

        <button class="btn btn-success">Salvar</button>

        <a href="<?= BASE_URL ?>/emprestimos?grupo_id=<?= $grupo_id ?>" 
           class="btn btn-secondary">
            Voltar
        </a>

    </form>

</div>
<script>
    document.querySelector('[name="valor"]').addEventListener('input', function() {

        let valor = parseFloat(this.value) || 0;

        // Simulação simples (ex: 10%)
        let juros = valor * 0.1;

        let total = valor + juros;

        document.getElementById('preview').value = 
            total.toLocaleString('pt-BR', {style:'currency', currency:'BRL'});
    });
</script>