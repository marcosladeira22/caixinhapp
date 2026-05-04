<?php
namespace Models;

use Core\Database;

/**
 * Model Pagamento
 * Responsável apenas por persistência e consulta de pagamentos
 */
class Pagamento
{
    /**
     * Registra um pagamento
     */
    public static function registrar(
        int $usuarioId,
        int $grupoId,
        string $mesReferencia,
        float $valor,
        string $dataPagamento,
        int $diasAtraso
    ): void {
        $db = Database::conectar();

        $stmt = $db->prepare(
            'INSERT INTO pagamentos
             (usuario_id, grupo_id, mes_referencia, valor, data_pagamento, dias_atraso)
             VALUES
             (:usuario_id, :grupo_id, :mes_referencia, :valor, :data_pagamento, :dias_atraso)'
        );

        $stmt->execute([
            ':usuario_id'     => $usuarioId,
            ':grupo_id'       => $grupoId,
            ':mes_referencia' => $mesReferencia,
            ':valor'          => $valor,
            ':data_pagamento' => $dataPagamento,
            ':dias_atraso'    => $diasAtraso
        ]);
    }

    /**
     * Lista pagamentos do grupo por mês com paginação
     */
    public static function listarPorGrupoMesPaginado(
        int $grupoId,
        string $mes,
        int $limite,
        int $offset
    ): array {
        $db = Database::conectar();

        $stmt = $db->prepare(
            'SELECT
                u.id AS usuario_id,
                u.nome,
                gu.quantidade_cotas,
                p.id AS pagamento_id,
                p.valor,
                p.data_pagamento
             FROM grupos_usuarios gu
             INNER JOIN usuarios u ON u.id = gu.usuario_id
             LEFT JOIN pagamentos p
               ON p.usuario_id = u.id
              AND p.grupo_id = gu.grupo_id
              AND p.mes_referencia = :mes
             WHERE gu.grupo_id = :grupo_id
             ORDER BY u.nome ASC
             LIMIT :limite OFFSET :offset'
        );

        $stmt->bindValue(':grupo_id', $grupoId, \PDO::PARAM_INT);
        $stmt->bindValue(':mes', $mes);
        $stmt->bindValue(':limite', $limite, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Conta quantos usuários existem no grupo (para paginação)
     */
    public static function contarPorGrupoMes(
        int $grupoId,
        string $mes
    ): int {
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

    /**
     * Soma total arrecadado de um grupo
     */
    public static function totalPagoPorGrupo(int $grupoId): float
    {
        $db = Database::conectar();

        $stmt = $db->prepare(
            'SELECT SUM(valor)
            FROM pagamentos
            WHERE grupo_id = :id'
        );
        $stmt->execute([':id' => $grupoId]);

        return (float) $stmt->fetchColumn();
    }
}