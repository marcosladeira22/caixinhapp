<?php
namespace Models;

use Core\Database;

class Usuario
{
    /**
     * Busca usuário pelo e-mail
     */
    public static function buscarPorEmail($email)
    {
        $db = Database::conectar();

        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Cria um novo usuário no sistema
     */
    public static function criar($dados)
    {
        $db = Database::conectar();

        $sql = "INSERT INTO usuarios 
            (nome, email, senha, telefone, sexo)
            VALUES
            (:nome, :email, :senha, :telefone, :sexo)";

        $stmt = $db->prepare($sql);
        $stmt->execute($dados);

        return $db->lastInsertId();
    }
}