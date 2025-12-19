<?php

namespace App\Models;

use Core\Database;

class Log
{
    // Conexão com o banco
    private $db;

    public function __construct()
    {
        // Instancia a conexão
        $this->db = Database::getConnection();
    }

    // Registra uma ação no log
    public function create($userId, $action, $description = null)
    {
        // SQL de inserção
        $sql = "INSERT INTO logs (user_id, action, description)
                VALUES (:user_id, :action, :description)";

        // Prepara a query
        $stmt = $this->db->prepare($sql);

        // Associa os valores
        $stmt->bindValue(':user_id', $userId);
        $stmt->bindValue(':action', $action);
        $stmt->bindValue(':description', $description);

        // Executa
        return $stmt->execute();
    }
}
