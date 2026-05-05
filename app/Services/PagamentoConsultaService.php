<?php
namespace Services;

use Models\Pagamento;
use Core\Paginator;

/**
 * Service responsável por consultas de pagamentos
 */
class PagamentoConsultaService
{
    /**
     * Lista pagamentos do grupo no mês atual com paginação
     */
    public static function listarPorGrupoMesAtual(
        int $grupoId,
        int $pagina,
        int $porPagina
    ): array {
        $mesAtual = date('Y-m-01');

        $total = Pagamento::contarPorGrupoMes($grupoId, $mesAtual);

        $paginator = new Paginator($total, $pagina, $porPagina);

        $pagamentos = Pagamento::listarPorGrupoMesPaginado(
            $grupoId,
            $mesAtual,
            $paginator->porPagina,
            $paginator->offset
        );

        return [
            'pagamentos' => $pagamentos,
            'paginator'  => $paginator,
            'mes'        => $mesAtual
        ];
    }
}