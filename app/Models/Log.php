<?php
namespace Models;

use Core\Database;

/**
 * Model responsável apenas por CONSULTA de logs
 */
class Log
{
    /**
     * Lista logs de um grupo com paginação
     */
    public static function listarPorGrupoPaginado(int $grupo_id, int $limite, int $offset): array
    {
        $db = \Core\Database::conectar();

        $sql = "SELECT 
                l.id,
                l.usuario_id,
                u.nome AS nome_usuario,
                l.acao,
                l.descricao,
                l.criado_em
            FROM logs l
            LEFT JOIN usuarios u 
                ON u.id = l.usuario_id
            WHERE l.descricao LIKE :grupo
            ORDER BY l.criado_em DESC
            LIMIT :limite OFFSET :offset
        ";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':grupo', "%grupo {$grupo_id}%");
        $stmt->bindValue(':limite', $limite, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Conta quantos logs existem para um grupo
     * Usado para paginação
     */
    public static function contarPorGrupo(int $grupo_id): int
    {
        $db = \Core\Database::conectar();

        $stmt = $db->prepare("SELECT COUNT(*)
                            FROM logs
                            WHERE descricao LIKE :grupo
                        ");

        $stmt->execute([
            ':grupo' => "%grupo {$grupo_id}%"
        ]);

        return (int) $stmt->fetchColumn();
    }
}