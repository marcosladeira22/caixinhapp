<?php

class GrupoUsuario {

    private $conn;
    private $table = "grupo_usuarios";

    public $grupo_id;
    public $usuario_id;
    public $nivel;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Vincular usuário ao grupo
    public function vincular() {

        $query = "INSERT INTO " . $this->table . "
            (grupo_id, usuario_id, nivel)
            VALUES (:grupo_id, :usuario_id, :nivel)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":grupo_id", $this->grupo_id);
        $stmt->bindParam(":usuario_id", $this->usuario_id);
        $stmt->bindParam(":nivel", $this->nivel);

        return $stmt->execute();
    }

    // Buscar grupos do usuário
    public function listarPorUsuario($usuario_id) {

        $query = "SELECT g.id, g.nome, g.descricao, g.valor_cota, gu.nivel
                FROM grupo_usuarios gu
                INNER JOIN grupos g ON g.id = gu.grupo_id
                WHERE gu.usuario_id = :usuario_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":usuario_id", $usuario_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Listar membros do Grupo
    public function listarMembros($grupo_id) {

        // Query correta (atenção aos espaços!)
        $query = "SELECT 
            u.id,
            u.nome,
            u.email,
            u.convite_status,
            u.convite_token,
            gu.nivel
          FROM grupo_usuarios gu
          JOIN usuarios u ON u.id = gu.usuario_id
          WHERE gu.grupo_id = :grupo_id";

        // Prepara a query
        $stmt = $this->conn->prepare($query);

        // Faz o bind corretamente
        $stmt->bindParam(":grupo_id", $grupo_id, PDO::PARAM_INT);

        // Executa a query
        $stmt->execute();

        // Retorna os dados
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Verifica se usuário pertence ao Grupo
    public function usuarioPertence($grupo_id, $usuario_id) {

        $query = "SELECT id FROM grupo_usuarios
                WHERE grupo_id = :grupo_id
                AND usuario_id = :usuario_id
                LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":grupo_id", $grupo_id);
        $stmt->bindParam(":usuario_id", $usuario_id);

        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    // Adicionar usuário ao grupo
    public function salvar() {

        $query = "INSERT INTO grupo_usuarios 
                (grupo_id, usuario_id, nivel)
                VALUES (:grupo_id, :usuario_id, :nivel)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":grupo_id", $this->grupo_id);
        $stmt->bindParam(":usuario_id", $this->usuario_id);
        $stmt->bindParam(":nivel", $this->nivel);

        return $stmt->execute();
    }
}