<?php
namespace Services;

use Models\Log;

/**
 * Service responsável por orquestrar logs de auditoria
 */
class LogService
{
    /**
     * Registra uma ação no sistema
     *
     * @param int|null $usuarioId Usuário que executou a ação
     * @param string   $acao      Tipo da ação (ex: PAGAMENTO, EMPRESTIMO)
     * @param string   $descricao Descrição detalhada
     */
    public static function registrar(
        ?int $usuarioId,
        string $acao,
        string $descricao
    ): void {
        Log::registrar($usuarioId, $acao, $descricao);
    }
}