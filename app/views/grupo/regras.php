<h4>💸 Regras de Empréstimo</h4>

<form method="POST" action="<?= BASE_URL ?>/regras/salvar">

    <input type="hidden" name="grupo_id" value="<?= $grupo_id ?>">
    
    <div class="mb-3">
        <label class="form-label">Valor mínimo</label>
        <input type="number" step="0.01" name="valor_minimo" value="<?= $regra['valor_minimo'] ?? '' ?>" required class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Valor máximo</label>
        <input type="number" step="0.01" name="valor_maximo" value="<?= $regra['valor_maximo'] ?? '' ?>" required class="form-control">
    </div>

   <div class="row g-3">
        <!-- Juros Inicial -->
        <div class="col-12 col-md-6">
            <div class="mb-3">
                <label class="form-label">Juros inicial</label>
                <div class="d-flex gap-2">
                    <select name="juros_inicial_tipo" class="form-select">
                        <option value="fixo">Fixo</option>
                        <option value="percentual">Percentual</option>
                    </select>

                    <input type="number" step="0.01" 
                        name="juros_inicial_valor" 
                        value="<?= $regra['juros_inicial_valor'] ?? '' ?>" 
                        class="form-control" placeholder="0,00">
                </div>
            </div>
        </div>

        <!-- Juros Atraso -->
        <div class="col-12 col-md-6">
            <div class="mb-3">
                <label class="form-label">Juros atraso</label>
                <div class="d-flex gap-2">
                    <select name="juros_atraso_tipo" class="form-select">
                        <option value="fixo">Fixo</option>
                        <option value="percentual">Percentual</option>
                    </select>

                    <input type="number" step="0.01"
                        name="juros_atraso_valor"
                        value="<?= $regra['juros_atraso_valor'] ?? '' ?>"
                        class="form-control" placeholder="0,00">
                </div>
            </div>
        </div>

    </div>

    <div class="mb-3">
        <label class="form-label">Dias de tolerância</label>
        <input type="number" name="dias_tolerancia" value="<?= $regra['dias_tolerancia'] ?? 0 ?>" class="form-control">
    </div>
    
    <br><br>

    <button class="btn btn-success">Salvar</button>

</form>