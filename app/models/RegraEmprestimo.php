<?php

class RegraEmprestimo {

    private $conn;
    private $table = "regras_emprestimo";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function buscarPorGrupo($grupo_id) {

        $query = "SELECT * FROM regras_emprestimo 
                  WHERE grupo_id = :grupo_id LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":grupo_id", $grupo_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>