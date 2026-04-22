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
}