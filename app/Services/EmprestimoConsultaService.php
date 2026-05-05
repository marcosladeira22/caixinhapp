<?php
namespace Services;

use Models\Emprestimo;
use Core\Paginator;

/**
 * Service responsável por consultas de empréstimos
 */
class EmprestimoConsultaService
{
    /**
     * Lista empréstimos do grupo com paginação
     */
    public static function listarPorGrupo(
        int $grupoId,
        int $paginaAtual,
        int $porPagina
    ): array {
        $total = Emprestimo::contarPorGrupo($grupoId);

        $paginator = new Paginator(
            $total,
            $paginaAtual,
            $porPagina
        );

        $emprestimos = Emprestimo::listarPorGrupoPaginado(
            $grupoId,
            $paginator->porPagina,
            $paginator->offset
        );

        return [
            'emprestimos' => $emprestimos,
            'paginator'   => $paginator
        ];
    }

    /**
     * Lista empréstimos inadimplentes
     */
    public static function listarInadimplentes(int $grupoId): array
    {
        return Emprestimo::listarPorStatus($grupoId, 'ATRASADO');
    }
}