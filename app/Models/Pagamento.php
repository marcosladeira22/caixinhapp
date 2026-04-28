<?php
namespace Models;

use Core\Database;
use DateTime;

class Pagamento
{
    /**
     * Registra um pagamento
     */
    public static function registrar($dados)
    {
        $db = Database::conectar();

        $sql = "INSERT INTO pagamentos
            (usuario_id, grupo_id, mes_referencia, valor, data_pagamento, dias_atraso)
            VALUES
            (:usuario_id, :grupo_id, :mes_referencia, :valor, :data_pagamento, :dias_atraso)";

        $stmt = $db->prepare($sql);
        $stmt->execute($dados);
    }

    /**
     * Calcula dias de atraso com base no vencimento
     */
    public static function calcularAtraso($vencimento, $pagamento)
    {
        $venc = new DateTime($vencimento);
        $pag = new DateTime($pagamento);

        if ($pag <= $venc) {
            return 0;
        }

        return $venc->diff($pag)->days;
    }

    /**
    * Lista pagamentos de um grupo por mês
    */
    public static function listarPorGrupoMes(int $grupo_id, string $mes): array
    {
        $db = \Core\Database::conectar();

        $sql = "SELECT u.id AS usuario_id, u.nome, gu.quantidade_cotas, p.id AS pagamento_id, p.valor, p.data_pagamento
                FROM grupos_usuarios gu
                JOIN usuarios u ON u.id = gu.usuario_id
                LEFT JOIN pagamentos p 
                ON p.usuario_id = u.id 
                AND p.grupo_id = gu.grupo_id
                AND p.mes_referencia = :mes
                WHERE gu.grupo_id = :grupo_id
            ";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':grupo_id' => $grupo_id,
            ':mes' => $mes
        ]);

        return $stmt->fetchAll();
    }

    /**
     * Lista pagamentos do grupo por mês com paginação
     */
    public static function listarPorGrupoMesPaginado(int $grupo_id, string $mes, int $limite, int $offset): array
    {
        $db = \Core\Database::conectar();

        $sql = "SELECT 
                u.id AS usuario_id,
                u.nome,
                gu.quantidade_cotas,
                p.id AS pagamento_id,
                p.valor,
                p.data_pagamento
            FROM grupos_usuarios gu
            JOIN usuarios u 
                ON u.id = gu.usuario_id
            LEFT JOIN pagamentos p
                ON p.usuario_id = u.id
            AND p.grupo_id = gu.grupo_id
            AND p.mes_referencia = :mes
            WHERE gu.grupo_id = :grupo_id
            ORDER BY u.nome ASC
            LIMIT :limite OFFSET :offset
        ";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':grupo_id', $grupo_id, \PDO::PARAM_INT);
        $stmt->bindValue(':mes', $mes);
        $stmt->bindValue(':limite', $limite, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     *
     */
    public static function contarPorGrupoMes(int $grupo_id, string $mes): int
    {
        $db = \Core\Database::conectar();

        $stmt = $db->prepare("SELECT COUNT(*)
                            FROM grupos_usuarios gu
                            WHERE gu.grupo_id = :grupo_id
                            ");

        $stmt->execute([':grupo_id' => $grupo_id]);
        return (int) $stmt->fetchColumn();
    }
}