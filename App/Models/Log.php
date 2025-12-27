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

    // Busca logs com filtros e paginação
    public function searchPaginated($search, $action, $limit, $offset)
    {
        // SQL base
        $sql = "
            SELECT logs.*, users.name
            FROM logs
            LEFT JOIN users ON users.id = logs.user_id
            WHERE 1 = 1
        ";

        // Parâmetros dinâmicos
        $params = [];

        // Filtro de busca (nome do usuário ou descrição)
        if (!empty($search)) {
            $sql .= " AND (users.name LIKE :search OR logs.description LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }

        // Filtro por ação
        if (!empty($action)) {
            $sql .= " AND logs.action = :action";
            $params[':action'] = $action;
        }

        // Ordenação e paginação
        $sql .= " ORDER BY logs.created_at DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);

        // Bind dos filtros
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        // Bind da paginação
        $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, \PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Conta logs com filtros
    public function countFiltered($search, $action)
    {
        $sql = "SELECT COUNT(*)
                FROM logs
                LEFT JOIN users ON users.id = logs.user_id
                WHERE 1 = 1
            ";

        $params = [];

        if (!empty($search)) {
            $sql .= " AND (users.name LIKE :search OR logs.description LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }

        if (!empty($action)) {
            $sql .= " AND logs.action = :action";
            $params[':action'] = $action;
        }

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();

        return $stmt->fetchColumn();
    }

}
