<?php

// Define o namespace da classe
namespace App\Models;
use Core\Database;
use PDO;

// Classe responsável pelos dados do usuário.
class User
{
    // Guarda a conexão
    private $db;

    public function __construct()
    {
        // Pega a conexão PDO
        $this->db = Database::getConnection();
    }

    // Busca todos os usuários
    public function getAll()
    {
        // Prepara a query
        $stmt = $this->db->prepare("SELECT * FROM users");

        // Executa a query
        $stmt->execute();

        // Retorna os dados como array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
