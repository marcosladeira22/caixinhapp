<?php
namespace Services;

use Models\Emprestimo;

/**
 * Service responsável por consultas de empréstimos pendentes
 */
class AprovacaoEmprestimoConsultaService
{
    /**
     * Lista empréstimos pendentes de aprovação em um grupo
     */
    public static function listarPendentes(int $grupoId): array
    {
        return Emprestimo::listarPorStatus($grupoId, 'PENDENTE');
    }
}