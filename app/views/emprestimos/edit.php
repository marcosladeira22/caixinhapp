<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <h4>✏️ Editar Empréstimo</h4>

        <a href="<?= BASE_URL ?>/emprestimos?grupo_id=<?= $grupo_id ?>" 
           class="btn btn-outline-secondary mb-3">
            ← Voltar
        </a>
    </div>

    <hr>

    <form method="POST" action="<?= BASE_URL ?>/emprestimos/update">

        <input type="hidden" name="id" value="<?= $emprestimo['id'] ?>">

        <div class="mb-3">
            <label class="form-label fw-bold">Valor</label>

            <input type="number"
                   step="0.01"
                   name="valor"
                   value="<?= $emprestimo['valor'] ?>"
                   class="form-control"
                   required>
        </div>

        <button class="btn btn-primary">
            💾 Salvar alterações
        </button>

    </form>

</div>