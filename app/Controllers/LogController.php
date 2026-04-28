<?php
namespace Controllers;

use Core\Controller;
use Core\Autenticacao;
use Core\Permissao;
use Models\Log;

/**
 * Controller responsável pela visualização dos logs
 */
class LogController extends Controller
{
    public function index()
    {
        \Core\Autenticacao::verificar();

        $grupo_id = $_GET['grupo_id'] ?? null;
        if (!$grupo_id) {
            die('Grupo não informado.');
        }

        \Core\Permissao::admin($grupo_id);

        // Paginação
        $paginaAtual = (int)($_GET['page'] ?? 1);
        $porPagina   = (int)($_GET['per_page'] ?? 10);

        // Total de registros
        $total = \Models\Log::contarPorGrupo($grupo_id);

        // Paginator
        $paginator = new \Core\Paginator($total, $paginaAtual, $porPagina);

        // Logs paginados
        $logs = \Models\Log::listarPorGrupoPaginado(
            $grupo_id,
            $paginator->porPagina,
            $paginator->offset
        );

        $this->view('logs/index', [
            'logs'      => $logs,
            'grupo_id'  => $grupo_id,
            'paginator' => $paginator
        ]);
    }
}