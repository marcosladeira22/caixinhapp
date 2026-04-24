<h3>Fechamento da Caixinha</h3>

<div class="alert alert-warning">
    <strong>Atenção:</strong> Após o fechamento, nenhuma operação será permitida.
</div>

<?= base_url("?rota=fechamento@fechar") ?>
    <input type="hidden" name="grupo_id" value="<?= $grupo_id ?>">
    <button class="btn btn-danger">
        Confirmar Fechamento
    </button>
</form>
