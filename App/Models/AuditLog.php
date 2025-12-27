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

    public function log(array $data): void
    {
        $sql = "INSERT INTO audit_logs
                (user_id, role, action, controller, method, ip_address)
                VALUES
                (:user_id, :role, :action, :controller, :method, :ip)
            ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':user_id'    => $data['user_id'],
            ':role'       => $data['role'],
            ':action'     => $data['action'],
            ':controller' => $data['controller'],
            ':method'     => $data['method'],
            ':ip'         => $data['ip'],
        ]);
    }

    public function all(): array
    {
        $stmt = $this->db->query("SELECT * FROM audit_logs ORDER BY created_at DESC");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}