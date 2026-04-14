<?php

class Pagamento {

    private $conn;
    private $table = "pagamentos";

    public $grupo_id;
    public $usuario_id;
    public $mes_referencia;
    public $valor_pago;
    public $status;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Salvar / Atualizar Pagamento
    public function salvar() {

        //Verificar se já existe pagamento
        $query = "SELECT id FROM " . $this->table . "
                WHERE grupo_id = :grupo_id
                AND usuario_id = :usuario_id
                AND mes_referencia = :mes_referencia";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":grupo_id", $this->grupo_id);
        $stmt->bindParam("usuario_id", $this->usuario_id);
        $stmt->bindParam("mes_referencia", $this->mes_referencia);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            
            // UPDATE
            $query = "UPDATE " . $this->table . "
            SET valor_pago = :valor_pago, status = :status
            WHERE grupo_id = :grupo_id
            AND usuario_id = :usuario_id
            AND mes_referencia = :mes_referencia";

        } else {
            // INSERT
            $query = "INSERT INTO " . $this->table . "
                    (grupo_id, usuario_id, mes_referencia, valor_pago, status)
                    VALUES (:grupo_id, :usuario_id, :mes_referencia, :valor_pago, :status)";
            
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":grupo_id", $this->grupo_id);
            $stmt->bindParam(":usuario_id", $this->usuario_id);
            $stmt->bindParam(":mes_referencia", $this->mes_referencia);
            $stmt->bindParam("valor_pago", $this->valor_pago);
            $stmt->bindParam(":status", $this->status);

            return $stmt->execute();
        }
    }

    // Listar Pagamentos do mês
    public function listarPorMes($grupo_id, $mes) {
        
        $query = "SELECT usuario_id, status, valor_pago FROM pagamentos
                WHERE grupo_id = :grupo_id AND mes_referencia = :mes";
        
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":grupo_id", $grupo_id);
        $stmt->bindParam(":mes", $mes);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}

?>