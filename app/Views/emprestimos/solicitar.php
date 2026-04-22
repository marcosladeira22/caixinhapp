<h3>Solicitar Empréstimo</h3>

<?php if (!empty($erro)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<form method="post">

    <div class="mb-3">
        <label>Valor desejado</label>
        <input type="number" step="0.01" name="valor" class="form-control" required>
    </div>
    <div class="alert alert-info mt-3" id="preview" style="display:none"></div>

    <button class="btn btn-primary">Enviar Solicitação</button>
    <a href="<?= base_url("?rota=dashboard@grupo&grupo_id={$grupo_id}") ?>" class="btn btn-secondary">
        Cancelar
    </a>

</form>
<script>
    const valorInput = document.querySelector('input[name="valor"]');
    const preview = document.getElementById('preview');

    valorInput.addEventListener('input', () => {
    const valor = parseFloat(valorInput.value || 0);
    if (valor > 0) {
        // Este endpoint pode retornar JSON com taxa/total (opcional)
        preview.style.display = 'block';
        preview.innerHTML = `
        <strong>Resumo:</strong><br>
        Valor solicitado: R$ ${valor.toFixed(2)}<br>
        A taxa e o total serão calculados conforme as regras do grupo.
        `;
    } else {
        preview.style.display = 'none';
    }
    });
</script>