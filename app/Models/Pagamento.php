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
}