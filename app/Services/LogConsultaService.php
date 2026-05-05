<?php
namespace Services;

use Models\Log;
use Core\Paginator;

/**
 * Service responsável por consulta de logs
 */
class LogConsultaService
{
    /**
     * Lista logs de um grupo com paginação
     */
    public static function listarPorGrupo(
        int $grupoId,
        int $pagina,
        int $porPagina
    ): array {
        $total = Log::contarPorGrupo($grupoId);

        $paginator = new Paginator($total, $pagina, $porPagina);

        $logs = Log::listarPorGrupoPaginado(
            $grupoId,
            $paginator->porPagina,
            $paginator->offset
        );

        return [
            'logs'      => $logs,
            'paginator' => $paginator
        ];
    }
}