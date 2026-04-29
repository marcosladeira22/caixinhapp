<?php
namespace Models;

use Core\Database;

/**
 * Model GrupoUsuario
 * Representa o vínculo entre usuário e grupo
 */
class GrupoUsuario
{
    /**
     * Associa um usuário a um grupo
     */
    public static function adicionar(
        int $usuarioId,
        int $grupoId,
        string $nivel,
        int $quantidadeCotas
    ): void {
        $db = Database::conectar();

        $stmt = $db->prepare(
            'INSERT INTO grupos_usuarios
             (usuario_id, grupo_id, nivel, quantidade_cotas)
             VALUES
             (:usuario_id, :grupo_id, :nivel, :quantidade_cotas)'
        );

        $stmt->execute([
            ':usuario_id'       => $usuarioId,
            ':grupo_id'         => $grupoId,
            ':nivel'            => $nivel,
            ':quantidade_cotas' => $quantidadeCotas
        ]);
    }

    /**
     * Retorna o nível do usuário em um grupo
     */
    public static function nivelNoGrupo(
        int $usuarioId,
        int $grupoId
    ): ?string {
        $db = Database::conectar();

        $stmt = $db->prepare(
            'SELECT nivel
             FROM grupos_usuarios
             WHERE usuario_id = :usuario_id
               AND grupo_id = :grupo_id
               AND ativo = 1
             LIMIT 1'
        );

        $stmt->execute([
            ':usuario_id' => $usuarioId,
            ':grupo_id'   => $grupoId
        ]);

        $resultado = $stmt->fetch();

        return $resultado['nivel'] ?? null;
    }

    /**
     * Busca o vínculo ativo de um usuário em um grupo
     */
    public static function buscarPorUsuarioEGrupo(
        int $usuarioId,
        int $grupoId
    ): ?array {
        $db = Database::conectar();

        $stmt = $db->prepare(
            'SELECT *
             FROM grupos_usuarios
             WHERE usuario_id = :usuario_id
               AND grupo_id = :grupo_id
               AND ativo = 1
             LIMIT 1'
        );

        $stmt->execute([
            ':usuario_id' => $usuarioId,
            ':grupo_id'   => $grupoId
        ]);

        return $stmt->fetch() ?: null;
    }

    /**
     * Busca um vínculo específico pelo ID
     */
    public static function buscarPorId(int $id): ?array
    {
        $db = Database::conectar();

        $stmt = $db->prepare(
            'SELECT *
             FROM grupos_usuarios
             WHERE id = :id
             LIMIT 1'
        );

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch() ?: null;
    }

    /**
     * Atualiza dados do usuário dentro do grupo
     */
    public static function atualizar(
        int $id,
        int $quantidadeCotas,
        string $nivel,
        bool $ativo
    ): void {
        $db = Database::conectar();

        $stmt = $db->prepare(
            'UPDATE grupos_usuarios
             SET quantidade_cotas = :quantidade_cotas,
                 nivel = :nivel,
                 ativo = :ativo
             WHERE id = :id'
        );

        $stmt->execute([
            ':quantidade_cotas' => $quantidadeCotas,
            ':nivel'            => $nivel,
            ':ativo'            => $ativo ? 1 : 0,
            ':id'               => $id
        ]);
    }

    /**
     * Lista usuários de um grupo com paginação
     */
    public static function listarPorGrupoPaginado(
        int $grupoId,
        int $limite,
        int $offset
    ): array {
        $db = Database::conectar();

        $stmt = $db->prepare(
            'SELECT
                gu.id AS grupo_usuario_id,
                gu.usuario_id,
                gu.quantidade_cotas,
                gu.nivel,
                gu.ativo,
                u.nome,
                u.email
             FROM grupos_usuarios gu
             INNER JOIN usuarios u ON u.id = gu.usuario_id
             WHERE gu.grupo_id = :grupo_id
             ORDER BY u.nome ASC
             LIMIT :limite OFFSET :offset'
        );

        $stmt->bindValue(':grupo_id', $grupoId, \PDO::PARAM_INT);
        $stmt->bindValue(':limite', $limite, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Conta quantos usuários existem em um grupo
     * Usado para paginação
     */
    public static function contarPorGrupo(int $grupoId): int
    {
        $db = Database::conectar();

        $stmt = $db->prepare(
            'SELECT COUNT(*)
             FROM grupos_usuarios
             WHERE grupo_id = :grupo_id'
        );

        $stmt->execute([
            ':grupo_id' => $grupoId
        ]);

        return (int) $stmt->fetchColumn();
    }
}