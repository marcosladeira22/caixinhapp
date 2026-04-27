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

    /**
     * Busca usuário pelo ID
     */
    public static function buscarPorId(int $id): ?array
    {
        $db = \Core\Database::conectar();

        $stmt = $db->prepare("SELECT * FROM usuarios WHERE id = :id");
        $stmt->execute([':id' => $id]);

        return $stmt->fetch() ?: null;
    }

    /**
     * Atualiza dados básicos do usuário
     */
    public static function atualizarDadosBasicos(int $id, string $nome, ?string $telefone, string $sexo): void 
    {
        $db = \Core\Database::conectar();

        $stmt = $db->prepare("UPDATE usuarios SET nome = :nome, telefone = :telefone, sexo = :sexo WHERE id = :id");

        $stmt->execute([
            ':nome'     => $nome,
            ':telefone' => $telefone,
            ':sexo'     => $sexo,
            ':id'       => $id
        ]);
    }

    /**
     * Atualiza senha do usuário
     */
    public static function atualizarSenha(int $id, string $senha): void
    {
        $db = \Core\Database::conectar();

        $stmt = $db->prepare("UPDATE usuarios SET senha = :senha WHERE id = :id ");

        $stmt->execute([
            ':senha' => password_hash($senha, PASSWORD_DEFAULT),
            ':id'    => $id
        ]);
    }
}