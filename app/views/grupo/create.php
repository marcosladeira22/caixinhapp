<div class="container mt-5">

    <h3>Criar Grupo</h3>

    <form method="POST" action="<?= BASE_URL ?>/grupos/store">

        <div class="mb-3">
            <label class="form-label">Nome do Grupo</label>
            <input type="text" name="nome" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Descrição</label>
            <textarea name="descricao" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Valor da Cota</label>
            <input type="number" step="0.01" name="valor_cota" class="form-control" required>
        </div>

        <button class="btn btn-success">Criar Grupo</button>

    </form>

</div>