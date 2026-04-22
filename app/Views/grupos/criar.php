<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Criar Grupo</title>
</head>
<body class="bg-light">

    <div class="container mt-5">
        <div class="col-md-6 offset-md-3">
            <div class="card shadow p-4">

                <h4 class="mb-4 text-center">Criar Nova Caixinha</h4>

                <form method="post">

                    <div class="mb-3">
                        <label>Nome do Grupo</label>
                        <input type="text" name="nome" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Valor da Cota (R$)</label>
                        <input type="number" step="0.01" name="valor_cota" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Empréstimo Mínimo (R$)</label>
                        <input type="number" step="0.01" name="emprestimo_min" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Empréstimo Máximo (R$)</label>
                        <input type="number" step="0.01" name="emprestimo_max" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Tipo de Taxa</label>
                        <select name="taxa_tipo" class="form-control">
                            <option value="fixo">Valor Fixo</option>
                            <option value="percentual">Percentual</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Valor da Taxa</label>
                        <input type="number" step="0.01" name="taxa_valor" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Tipo de Juros por Atraso</label>
                        <select name="juros_tipo" class="form-control">
                            <option value="fixo">Valor Fixo</option>
                            <option value="percentual">Percentual</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Valor do Juros</label>
                        <input type="number" step="0.01" name="juros_valor" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Dias de Tolerância</label>
                        <input type="number" name="dias_tolerancia" class="form-control" value="0">
                    </div>

                    <div class="d-flex justify-content-between">
                        <button class="btn btn-success">Criar Grupo</button>
                        <a href="<?= base_url('?rota=dashboard@index') ?>" class="btn btn-secondary">
                            Cancelar
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>

</body>
</html>
