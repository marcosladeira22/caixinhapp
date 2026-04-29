<?php
namespace Models;

use Core\Database;

/**
 * Model de Usuário
 * Responsável apenas por persistência
 */
class Usuario
{
    /**
     * Busca usuário pelo e-mail
     */
    public static function buscarPorEmail(string $email): ?array
    {
        $db = Database::conectar();

        $stmt = $db->prepare(
            'SELECT * FROM usuarios WHERE email = :email LIMIT 1'
        );
        $stmt->execute([
            ':email' => $email
        ]);

        return $stmt->fetch() ?: null;
    }

    /**
     * Busca usuário pelo ID
     */
    public static function buscarPorId(int $id): ?array
    {
        $db = Database::conectar();

        $stmt = $db->prepare(
            'SELECT * FROM usuarios WHERE id = :id LIMIT 1'
        );
        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch() ?: null;
    }

    /**
     * Cria um novo usuário no sistema
     */
    public static function criar(
        string $nome,
        string $email,
        string $senhaHash,
        ?string $telefone,
        string $sexo
    ): int {
        $db = Database::conectar();

        $stmt = $db->prepare(
            'INSERT INTO usuarios (nome, email, senha, telefone, sexo)
             VALUES (:nome, :email, :senha, :telefone, :sexo)'
        );

        $stmt->execute([
            ':nome'     => $nome,
            ':email'    => $email,
            ':senha'    => $senhaHash,
            ':telefone' => $telefone,
            ':sexo'     => $sexo
        ]);

        return (int) $db->lastInsertId();
    }

    /**
     * Atualiza dados básicos do usuário
     */
    public static function atualizarDadosBasicos(
        int $id,
        string $nome,
        ?string $telefone,
        string $sexo
    ): void {
        $db = Database::conectar();

        $stmt = $db->prepare(
            'UPDATE usuarios
             SET nome = :nome, telefone = :telefone, sexo = :sexo
             WHERE id = :id'
        );

        $stmt->execute([
            ':nome'     => $nome,
            ':telefone' => $telefone,
            ':sexo'     => $sexo,
            ':id'       => $id
        ]);
    }

    /**
     * Atualiza a senha do usuário
     */
    public static function atualizarSenha(int $id, string $senhaHash): void
    {
        $db = Database::conectar();

        $stmt = $db->prepare(
            'UPDATE usuarios SET senha = :senha WHERE id = :id'
        );

        $stmt->execute([
            ':senha' => $senhaHash,
            ':id'    => $id
        ]);
    }
}