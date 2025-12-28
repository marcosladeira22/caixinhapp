<?php

namespace App\Models;

use PDO;

class AuditLog
{
    private PDO $db;

    public function __construct()
    {
        $this->db = \Core\Database::getConnection();
    }

    // PADRÃO DO SISTEMA
    public function create(array $data): void
    {
        $sql = "INSERT INTO audit_logs
                (user_id, role, action, controller, method, params, ip_address, created_at)
                VALUES
                (:user_id, :role, :action, :controller, :method, :params, :ip, NOW())
            ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':user_id'    => $data['user_id'],
            ':role'       => $data['role'],
            ':action'     => $data['action'],
            ':controller' => $data['controller'],
            ':method'     => $data['method'],
            ':params'     => $data['params'] ?? null,
            ':ip'         => $data['ip_address'] ?? null,
        ]);
    }

    public function all(): array
    {
        $stmt = $this->db->query("SELECT * FROM audit_logs ORDER BY created_at DESC");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchPaginated($userId, $action, $limit, $offset)
    {
        $sql = "SELECT * FROM audit_logs WHERE 1=1";
        $params = [];

        if ($userId) {
            $sql .= " AND user_id = :user_id";
            $params[':user_id'] = $userId;
        }

        if ($action) {
            $sql .= " AND action LIKE :action";
            $params[':action'] = "%{$action}%";
        }

        $sql .= " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countFiltered($userId, $action)
    {
        $sql = "SELECT COUNT(*) FROM audit_logs WHERE 1=1";
        $params = [];

        if ($userId) {
            $sql .= " AND user_id = :user_id";
            $params[':user_id'] = $userId;
        }

        if ($action) {
            $sql .= " AND action LIKE :action";
            $params[':action'] = "%{$action}%";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchColumn();
    }

}
