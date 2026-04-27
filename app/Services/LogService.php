<?php
namespace Services;

use Core\Database;

/**
 * Serviço responsável por registrar logs de auditoria
 */
class LogService
{
    /**
     * Registra uma ação no sistema
     *
     * @param int|null $usuario_id Usuário que executou a ação
     * @param string   $acao       Tipo da ação (ex: PAGAMENTO, EMPRESTIMO)
     * @param string   $descricao  Descrição detalhada
     */
    public static function registrar(?int $usuario_id, string $acao, string $descricao): void
    {
        $db = Database::conectar();

        $sql = "INSERT INTO logs (usuario_id, acao, descricao)
                VALUES (:usuario_id, :acao, :descricao)";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':usuario_id' => $usuario_id,
            ':acao'       => $acao,
            ':descricao'  => $descricao
        ]);
    }
}