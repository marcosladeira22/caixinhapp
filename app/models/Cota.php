<?php 

    class Cota {

        private $conn;
        private $table = "cotas";

        public $grupo_id;
        public $usuario_id;
        public $quantidade;
        public $valor_unitario;

        public function __construct($db) {
            
            // Conexão com banco (PDO)
            $this->conn = $db;

            // Nome da tabela (string fixa)
            $this->table = "cotas";
        }

        public function salvar() {

            // Verifica se já existe
            $query = "SELECT id FROM " . $this->table . " 
                    WHERE grupo_id = :grupo_id
                    AND usuario_id = :usuario_id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":grupo_id", $this->grupo_id);
            $stmt->bindParam(":usuario_id", $this->usuario_id);
            $stmt->execute();

            // Se existir → UPDATE
            if ($stmt->rowCount() > 0) {
                $query = "UPDATE " . $this->table . " 
                         SET quantidade = :quantidade,
                         valor_unitario = :valor_unitario
                         WHERE grupo_id = :grupo_id
                         AND usuario_id = :usuario_id";
            } else {

                // Se não existir → INSERT
                $query = "INSERT INTO " . $this->table . "
                (grupo_id, usuario_id, quantidade, valor_unitario)
                VALUES (:grupo_id, :usuario_id, :quantidade, :valor_unitario)";
            }

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":grupo_id", $this->grupo_id);
            $stmt->bindParam(":usuario_id", $this->usuario_id);
            $stmt->bindParam(":quantidade", $this->quantidade);
            $stmt->bindParam(":valor_unitario", $this->valor_unitario);

            return $stmt->execute();
        }

        // Listar Cotas do Grupo (com usuários)
        public function listarPorGrupo($grupo_id) {

            $query = "SELECT u.id as usuario_id, u.nome,
                    COALESCE(c.quantidade, 0) as quantidade
                    FROM grupo_usuarios gu
                    INNER JOIN usuarios u ON u.id = gu.usuario_id
                    LEFT JOIN cotas c 
                    ON c.usuario_id = u.id 
                    AND c.grupo_id = gu.grupo_id
                    WHERE gu.grupo_id = :grupo_id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":grupo_id", $grupo_id);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

    }
?>