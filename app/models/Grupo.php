<?php

class Grupo {

    private $conn;
    private $table = "grupos";

    public $id;
    public $nome;
    public $descricao;
    public $valor_cota;
    public $criado_por;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Criar grupo
    public function criar() {

        $query = "INSERT INTO " . $this->table . "
                (nome, descricao, valor_cota, criado_por)
                VALUES (:nome, :descricao, :valor_cota, :criado_por)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":descricao", $this->descricao);
        $stmt->bindParam(":valor_cota", $this->valor_cota);
        $stmt->bindParam(":criado_por", $this->criado_por);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId(); // retorna ID grupo
        }

        return false;
    }

    // Buscar grupo por ID
    public function buscarPorId($id) {

        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id", $id);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Listar grupos do usuários
    public function listarPorUsuario($usuario_id) {
        // Query que traz os grupos que o usuário participa
        $query = "SELECT g.*, gu.nivel
                FROM grupos g
                INNER JOIN grupo_usuarios gu 
                ON gu.grupo_id = g.id
                WHERE gu.usuario_id = :usuario_id
                ORDER BY g.created_at DESC";

        // Prepara a query
        $stmt = $this->conn->prepare($query);

        // Bind do usuário
        $stmt->bindParam(":usuario_id", $usuario_id);

        // Executa
        $stmt->execute();

        // Retorna lista de grupos
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}

?>