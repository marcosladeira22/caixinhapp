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

    // CREATE — insere usuário
    public function create($name, $email)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO users (name, email) VALUES (:name, :email)"
        );

        return $stmt->execute([
            'name'  => $name,
            'email' => $email
        ]);
    }

    // READ — lista todos
    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // READ — busca um usuário
    public function find($id)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE id = :id"
        );

        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // UPDATE — atualiza usuário
    public function update($id, $name, $email)
    {
        $stmt = $this->db->prepare(
            "UPDATE users SET name = :name, email = :email WHERE id = :id"
        );

        return $stmt->execute([
            'id'    => $id,
            'name'  => $name,
            'email' => $email
        ]);
    }

    // DELETE — remove usuário
    public function delete($id)
    {
        $stmt = $this->db->prepare(
            "DELETE FROM users WHERE id = :id"
        );

        return $stmt->execute(['id' => $id]);
    }
}
