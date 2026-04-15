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

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
}
?>