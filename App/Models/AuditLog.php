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
}
