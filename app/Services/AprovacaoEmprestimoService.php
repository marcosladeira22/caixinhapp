<?php
namespace Services;

use Models\Emprestimo;
use Exception;

class AprovacaoEmprestimoService
{
    /**
     * Aprova um empréstimo
     */
    public static function aprovar(int $emprestimo_id): void
    {
        // Data de vencimento padrão: 30 dias
        $dataVencimento = date('Y-m-d', strtotime('+30 days'));

        Emprestimo::atualizarStatus(
            $emprestimo_id,
            'APROVADO',
            $dataVencimento
        );
    }

    /**
     * Rejeita um empréstimo
     */
    public static function rejeitar(int $emprestimo_id): void
    {
        Emprestimo::atualizarStatus(
            $emprestimo_id,
            'REJEITADO',
            null
        );
    }
}