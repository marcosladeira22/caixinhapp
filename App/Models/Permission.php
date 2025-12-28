<?php

namespace App\Models;

use Core\Database;
use PDO;

class Permission
{
    private $db;

    public function __construct()
    {
        // Usa a conexão Singleton do projeto
        $this->db = Database::getConnection();
    }

    /**
     * Retorna TODAS as permissões cadastradas
     */
    public function getAll()
    {
        $sql = "SELECT id, name, description FROM permissions ORDER BY name ASC";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retorna as permissões associadas a um role
     * Ex: ['delete_user', 'edit_user']
     */
    public function getPermissionsByRole($role)
    {
        $sql = "SELECT p.name
                FROM permissions p
                INNER JOIN role_permissions rp ON rp.permission_id = p.id
                WHERE rp.role = :role
            ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['role' => $role]);

        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * Remove TODAS as permissões de um role
     */
    public function removeAllFromRole($role)
    {
        $sql = "DELETE FROM role_permissions WHERE role = :role";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':role', $role);

        return $stmt->execute();
    }

    /**
     * Associa uma permissão a um role
     */
    public function assignPermissionToRole($role, $permissionName)
    {
        // Busca o ID da permissão pelo nome
        $sqlPermission = "SELECT id FROM permissions WHERE name = :name LIMIT 1";

        $stmt = $this->db->prepare($sqlPermission);
        $stmt->bindValue(':name', $permissionName);
        $stmt->execute();

        $permission = $stmt->fetch(PDO::FETCH_ASSOC);

        // Se a permissão não existir, aborta
        if (!$permission) {
            return false;
        }

        // Insere a relação role ↔ permissão
        $sqlInsert = "INSERT INTO role_permissions (role, permission_id) VALUES (:role, :permission_id)";

        $stmt = $this->db->prepare($sqlInsert);
        $stmt->bindValue(':role', $role);
        $stmt->bindValue(':permission_id', $permission['id']);

        return $stmt->execute();
    }

    /**
     * Método já existente usado pelo can()
     * (mantido exatamente como está no seu projeto)
     */
    public function roleHasPermission($role, $permission)
    {
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

    public static function loadByUser(int $userId): array
    {
        $db = Database::getConnection();

        $sql = "SELECT p.slug
                FROM permissions p
                JOIN role_permissions rp ON rp.permission_id = p.id
                JOIN users u ON u.role_id = rp.role_id
                WHERE u.id = :user
            ";

        $stmt = $db->prepare($sql);
        $stmt->execute(['user' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

}
