<?php
namespace Models;

use Core\Database;

/**
 * Model responsável apenas por CONSULTA de logs
 */
class Log
{
    /**
     * Lista logs por grupo
     */
    public static function listarPorGrupo(int $grupo_id, ?string $acao = null): array
    {
        $db = Database::conectar();

        $sql = "SELECT l.*, u.nome AS nome_usuario
            FROM logs l
            LEFT JOIN usuarios u ON u.id = l.usuario_id
            WHERE l.descricao LIKE :grupo
        ";

        $params = [':grupo' => "%grupo {$grupo_id}%"];

        // Filtro opcional por tipo de ação
        if ($acao) {
            $sql .= " AND l.acao = :acao";
            $params[':acao'] = $acao;
        }

        $sql .= " ORDER BY l.criado_em DESC";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }
}