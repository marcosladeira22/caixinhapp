<?php
namespace Models;

use Core\Database;

/**
 * Model Log
 * Responsável apenas por CONSULTA dos logs do sistema
 *
 * Observação:
 * A filtragem por grupo é feita via padrão textual na descrição.
 * Esta decisão foi mantida por compatibilidade com a estrutura atual.
 */
class Log
{
    /**
     * Lista logs de um grupo com paginação
     */
    public static function listarPorGrupoPaginado(
        int $grupoId,
        int $limite,
        int $offset
    ): array {
        $db = Database::conectar();

        $stmt = $db->prepare(
            'SELECT 
                l.id,
                l.usuario_id,
                u.nome AS nome_usuario,
                l.acao,
                l.descricao,
                l.criado_em
             FROM logs l
             LEFT JOIN usuarios u ON u.id = l.usuario_id
             WHERE l.descricao LIKE :grupo
             ORDER BY l.criado_em DESC
             LIMIT :limite OFFSET :offset'
        );

        $stmt->bindValue(':grupo', "%grupo {$grupoId}%");
        $stmt->bindValue(':limite', $limite, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Conta quantos logs existem para um grupo
     * Usado para paginação
     */
    public static function contarPorGrupo(int $grupoId): int
    {
        $db = Database::conectar();

        $stmt = $db->prepare(
            'SELECT COUNT(*)
             FROM logs
             WHERE descricao LIKE :grupo'
        );

        $stmt->execute([
            ':grupo' => "%grupo {$grupoId}%"
        ]);

        return (int) $stmt->fetchColumn();
    }
}