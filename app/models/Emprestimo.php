<?php

class Emprestimo {

    private $conn;
    private $table = "emprestimos";

    public $id;
    public $grupo_id;
    public $usuario_id;
    public $valor;
    public $valor_com_juros;
    public $juros_inicial;
    public $data_emprestimo;
    public $data_vencimento;
    public $status;

    // Construtor
    public function __construct($db) {
        $this->conn = $db;
    }

    // =========================
    // CALCULAR JUROS INICIAL
    // =========================
    public function calcularJurosInicial($valor, $regra) {

        if ($regra['juros_inicial_tipo'] === 'percentual') {
            return ($valor * $regra['juros_inicial_valor']) / 100;
        }

        return $regra['juros_inicial_valor'];
    }

    // =========================
    // CRIAR EMPRÉSTIMO
    // =========================
    public function criar() {

        $query = "INSERT INTO " . $this->table . "
            (grupo_id, usuario_id, valor, valor_com_juros, juros_inicial, data_emprestimo, data_vencimento, status)
            VALUES
            (:grupo_id, :usuario_id, :valor, :valor_com_juros, :juros_inicial, :data_emprestimo, :data_vencimento, :status)";

        $stmt = $this->conn->prepare($query);

        if (empty($this->status)) {
            $this->status = 'aberto';
        }

        $stmt->bindParam(":grupo_id", $this->grupo_id);
        $stmt->bindParam(":usuario_id", $this->usuario_id);
        $stmt->bindParam(":valor", $this->valor);
        $stmt->bindParam(":valor_com_juros", $this->valor_com_juros);
        $stmt->bindParam(":juros_inicial", $this->juros_inicial);
        $stmt->bindParam(":data_emprestimo", $this->data_emprestimo);
        $stmt->bindParam(":data_vencimento", $this->data_vencimento);
        $stmt->bindParam(":status", $this->status);

        return $stmt->execute();
    }

    // =========================
    // LISTAR POR GRUPO
    // =========================
    public function listarPorGrupo($grupo_id) {

        $query = "SELECT 
                    e.*,
                    u.nome
                  FROM " . $this->table . " e
                  INNER JOIN usuarios u ON u.id = e.usuario_id
                  WHERE e.grupo_id = :grupo_id
                  ORDER BY e.id DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":grupo_id", $grupo_id);
        $stmt->execute();

        $emprestimos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 🔥 ADICIONA DADOS CALCULADOS
        require_once __DIR__ . '/RegraEmprestimo.php';
        $regraModel = new RegraEmprestimo($this->conn);
        $regra = $regraModel->buscarPorGrupo($grupo_id);

        foreach ($emprestimos as &$e) {

            $hoje = date('Y-m-d');

            // dias totais de atraso
            $diasAtrasoTotal = floor((strtotime($hoje) - strtotime($e['data_vencimento'])) / 86400);

            $diasAtrasoTotal = max(0, $diasAtrasoTotal);

            // dias cobrados (descontando tolerância)
            $diasCobrados = max(0, $diasAtrasoTotal - ($regra['dias_tolerancia'] ?? 0));

            $e['dias_atraso'] = $diasAtrasoTotal;
            $e['dias_cobrados'] = $diasCobrados;
            $e['dias_tolerancia'] = $regra['dias_tolerancia'] ?? 0;
        }

        return $emprestimos;
    }

    // =========================
    // BUSCAR POR ID
    // =========================
    public function buscarPorId($id) {

        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // =========================
    // ATUALIZAR STATUS
    // =========================
    public function atualizarStatus() {

        $query = "UPDATE " . $this->table . "
                  SET status = :status
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

    // =========================
    // VERIFICAR ATRASO (FUTURO)
    // =========================
    public function verificarAtraso() {

        $query = "UPDATE " . $this->table . "
                  SET status = 'atrasado'
                  WHERE status = 'aberto'
                  AND data_vencimento < CURDATE()";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute();
    }

    // ====================================
    // TOTAL EMPRESTADO (dinheiro que saiu)
    // ====================================
    public function totalEmprestado($grupo_id) {

        $query = "SELECT SUM(valor) as total 
                FROM emprestimos 
                WHERE grupo_id = :grupo_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":grupo_id", $grupo_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    }

    // ====================================
    // TOTAL RECEBIDO (empréstimos pagos)
    // ====================================
    public function totalRecebido($grupo_id) {

        $query = "SELECT SUM(valor_com_juros) as total 
                FROM emprestimos 
                WHERE grupo_id = :grupo_id AND status = 'pago'";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":grupo_id", $grupo_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    }

    // ====================================
    // APLICA JUROS POR ATRASO AUTOMATICO
    // ====================================
    public function aplicarJurosAtrasoAutomatico($grupo_id) {

        $query = "SELECT * FROM emprestimos 
                WHERE grupo_id = :grupo_id 
                AND status != 'pago'";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":grupo_id", $grupo_id);
        $stmt->execute();

        $emprestimos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($emprestimos as $e) {

            $hoje = date('Y-m-d');

            // ⏱️ dias desde vencimento
            $dias = floor((strtotime($hoje) - strtotime($e['data_vencimento'])) / 86400);

            // 🔎 regra
            $queryRegra = "SELECT * FROM regras_emprestimo 
                        WHERE grupo_id = :grupo_id LIMIT 1";

            $stmtRegra = $this->conn->prepare($queryRegra);
            $stmtRegra->bindParam(":grupo_id", $grupo_id);
            $stmtRegra->execute();

            $regra = $stmtRegra->fetch(PDO::FETCH_ASSOC);

            if (!$regra) continue;

            // 🔥 DEFINE STATUS CORRETAMENTE
            if ($dias > 0) {
                $status = 'atrasado'; // venceu = atrasado SEMPRE
            } else {
                $status = 'aberto';
            }

            // aplica tolerância só no juros (não no status)
            $diasAtraso = max(0, $dias - $regra['dias_tolerancia']);

            // 💰 juros atraso
            if ($diasAtraso > 0) {

                if ($regra['juros_atraso_tipo'] === 'percentual') {

                    $jurosAtraso = ($e['valor'] * ($regra['juros_atraso_valor'] / 100)) * $diasAtraso;

                } else {

                    $jurosAtraso = $regra['juros_atraso_valor'] * $diasAtraso;
                }

            } else {
                $jurosAtraso = 0;
            }

            // 🔒 BASE FIXA (NUNCA MUDA)
            $base = $e['valor'] + $e['juros_inicial'];

            // 💣 valor correto
            $novoValor = $base + $jurosAtraso;

            $update = "UPDATE emprestimos 
                    SET valor_com_juros = :valor,
                        status = :status
                    WHERE id = :id";

            $stmtUp = $this->conn->prepare($update);
            $stmtUp->bindParam(":valor", $novoValor);
            $stmtUp->bindParam(":status", $status);
            $stmtUp->bindParam(":id", $e['id']);
            $stmtUp->execute();
        }
    }

    // =================
    // MARCAR COMO PAGO
    // =================
    public function marcarComoPago($id) {

        $query = "UPDATE emprestimos 
                SET status = 'pago'
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);

        return $stmt->execute();
    }

    // ===================
    // DELETE EMPRESTIMOS
    // ===================
    public function deletar($id) {

        $query = "DELETE FROM emprestimos WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);

        return $stmt->execute();
    }

    // ===================
    // TOTAL LUCRO JUROS - EMPRESTIMOS
    // ===================
    public function totalLucroJuros($grupo_id) {

        $query = "SELECT SUM((valor_com_juros - valor)) as lucro";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":grupo_id", $grupo_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC)['lucro'] ?? 0;
    }

}
?>