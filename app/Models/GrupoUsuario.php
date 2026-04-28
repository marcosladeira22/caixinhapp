<?php
namespace Models;

use Core\Database;

class GrupoUsuario
{
    // Associa um usuário a um grupo
    public static function adicionarUsuarioAoGrupo($usuario_id, $grupo_id, $nivel, $quantidade_cotas)
    {
        if (!$usuario_id) {
            throw new \Exception('Usuário inválido para associação ao grupo.');
        }

        $db = Database::conectar();

        $sql = "INSERT INTO grupos_usuarios 
            (usuario_id, grupo_id, nivel, quantidade_cotas)
            VALUES 
            (:usuario_id, :grupo_id, :nivel, :quantidade_cotas)";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':usuario_id'       => $usuario_id,
            ':grupo_id'         => $grupo_id,
            ':nivel'            => $nivel,
            ':quantidade_cotas' => $quantidade_cotas
        ]);
    }

    // Verifica nível do usuário em um grupo
    public static function nivelUsuarioNoGrupo($usuario_id, $grupo_id)
    {
        $db = Database::conectar();

        $sql = "SELECT nivel 
                FROM grupos_usuarios 
                WHERE usuario_id = :usuario_id 
                AND grupo_id = :grupo_id 
                AND ativo = 1";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':usuario_id' => $usuario_id,
            ':grupo_id' => $grupo_id
        ]);

        $resultado = $stmt->fetch();

        return $resultado['nivel'] ?? null;
    }

    /**
     * Retorna dados do usuário dentro do grupo
     */
    public static function buscar($usuario_id, $grupo_id)
    {
        $db = Database::conectar();

        $sql = "SELECT * FROM grupos_usuarios
                WHERE usuario_id = :usuario_id
                AND grupo_id = :grupo_id
                AND ativo = 1";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':usuario_id' => $usuario_id,
            ':grupo_id' => $grupo_id
        ]);

        return $stmt->fetch();
    }

    /**
     * Retorna lista do grupo
     */
    public static function listarPorGrupo(int $grupo_id): array
    {
        $db = \Core\Database::conectar();

        $sql = "SELECT gu.*, u.nome, u.email
                FROM grupos_usuarios gu
                JOIN usuarios u ON u.id = gu.usuario_id
                WHERE gu.grupo_id = :grupo_id";

        $stmt = $db->prepare($sql);
        $stmt->execute([':grupo_id' => $grupo_id]);

        return $stmt->fetchAll();
    }

    /**
     * Busca um vínculo específico pelo ID
     */
    public static function buscarPorId(int $id): ?array
    {
        $db = \Core\Database::conectar();

        $stmt = $db->prepare("SELECT * FROM grupos_usuarios WHERE id = :id");
        $stmt->execute([':id' => $id]);

        return $stmt->fetch() ?: null;
    }

    /**
     * Atualiza dados do usuário dentro do grupo
     */
    public static function atualizar(int $id, int $quantidade_cotas, string $nivel, int $ativo): void
    {
        $db = \Core\Database::conectar();

        $stmt = $db->prepare("UPDATE grupos_usuarios SET quantidade_cotas = :cotas, nivel = :nivel, ativo = :ativo WHERE id = :id");

        $stmt->execute([
            ':cotas' => $quantidade_cotas,
            ':nivel' => $nivel,
            ':ativo' => $ativo,
            ':id'    => $id
        ]);
    }

    /**
    * Lista usuários de um grupo com paginação
    */
    public static function listarPorGrupoPaginado(int $grupo_id, int $limite, int $offset): array
    {

        $db = \Core\Database::conectar();

        $sql = "SELECT 
                gu.id AS grupo_usuario_id,
                gu.usuario_id,
                gu.quantidade_cotas,
                gu.nivel,
                gu.ativo,
                u.nome,
                u.email
            FROM grupos_usuarios gu
            JOIN usuarios u 
                ON u.id = gu.usuario_id
            WHERE gu.grupo_id = :grupo_id
            ORDER BY u.nome ASC
            LIMIT :limite OFFSET :offset
            ";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':grupo_id', $grupo_id, \PDO::PARAM_INT);
        $stmt->bindValue(':limite', $limite, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Conta quantos usuários existem em um grupo
     * Usado para paginação
     */
    public static function contarPorGrupo(int $grupo_id): int
    {
        $db = \Core\Database::conectar();

        $stmt = $db->prepare("SELECT COUNT(*)
                        FROM grupos_usuarios
                        WHERE grupo_id = :grupo_id
                    ");

        $stmt->execute([
            ':grupo_id' => $grupo_id
        ]);

        return (int) $stmt->fetchColumn();
    }


}