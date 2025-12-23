<?php

namespace App\Models;

use Core\Database;
use PDO;

class Permission
{
    private $db;

    public function __construct()
    {
        // Usa a conexão Singleton
        $this->db = Database::getConnection();
    }

    // Verifica se um papel possui determinada permissão
    public function roleHasPermission($role, $permission)
    {
        // SQL para validar permissão
        $sql = "SELECT p.id
                FROM permissions p
                JOIN role_permissions rp ON rp.permission_id = p.id
                WHERE rp.role = :role
                AND p.name = :permission
                LIMIT 1
            ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':role', $role);
        $stmt->bindValue(':permission', $permission);
        $stmt->execute();

        return $stmt->fetch() !== false;
    }

    // Retorna todas as permissões
    public function all()
    {
        $sql = "SELECT * FROM permissions ORDER BY name";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Retorna permissões de um role
    public function getByRole($role)
    {
        $sql = "SELECT permission_id
                FROM role_permissions
                WHERE role = :role
            ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':role', $role);
        $stmt->execute();

        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC),'permission_id');
    }

    // Associa permissão ao role
    public function attach($role, $permissionId)
    {
        $sql = "INSERT IGNORE INTO role_permissions (role, permission_id)
                VALUES (:role, :permission_id)
            ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':role', $role);
        $stmt->bindValue(':permission_id', $permissionId);
        return $stmt->execute();
    }

    // Remove permissão do role
    public function detach($role, $permissionId)
    {
        $sql = "DELETE FROM role_permissions
                WHERE role = :role AND permission_id = :permission_id
            ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':role', $role);
        $stmt->bindValue(':permission_id', $permissionId);
        return $stmt->execute();
    }

}
