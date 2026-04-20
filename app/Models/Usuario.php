<?php
namespace Models;

use Core\Database;

class Usuario
{
    public static function buscarPorEmail($email)
    {
        $db = Database::conectar();
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch();
    }
}