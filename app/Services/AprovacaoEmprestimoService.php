<?php
namespace Services;

use Models\Emprestimo;
use Services\LogService;
use Core\Sessao;
use Exception;

/**
 * Service responsável por aprovação e rejeição de empréstimos
 */
class AprovacaoEmprestimoService
{
    /**
     * Aprova um empréstimo
     */
    public static function aprovar(int $emprestimoId): void
    {
        // Data de vencimento padrão: 30 dias
        $dataVencimento = date('Y-m-d', strtotime('+30 days'));

        Emprestimo::atualizarStatus(
            $emprestimoId,
            'APROVADO',
            $dataVencimento
        );

        LogService::registrar(
            Sessao::get('usuario_id'),
            'EMPRESTIMO_APROVADO',
            "Empréstimo {$emprestimoId} aprovado com vencimento em {$dataVencimento}"
        );
    }

    /**
     * Rejeita um empréstimo
     */
    public static function rejeitar(int $emprestimoId, ?string $motivo = null): void
    {
        Emprestimo::atualizarStatus(
            $emprestimoId,
            'REJEITADO',
            null
        );

        $descricao = "Empréstimo {$emprestimoId} rejeitado";
        if ($motivo) {
            $descricao .= " | Motivo: {$motivo}";
        }

        LogService::registrar(
            Sessao::get('usuario_id'),
            'EMPRESTIMO_REJEITADO',
            $descricao
        );
    }
}