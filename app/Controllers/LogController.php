<?php
namespace Controllers;

use Core\Controller;
use Core\Autenticacao;
use Core\Permissao;
use Services\LogConsultaService;

/**
 * Controller responsável pela visualização dos logs
 */
class LogController extends Controller
{
    public function index()
    {
        Autenticacao::exigirLogin();

        $grupoId = $_GET['grupo_id'] ?? null;
        if (!$grupoId) {
            $this->redirect('?rota=dashboard@index');
        }

        Permissao::exigirAdmin((int)$grupoId);

        $pagina    = (int)($_GET['page'] ?? 1);
        $porPagina = (int)($_GET['per_page'] ?? 10);

        $resultado = LogConsultaService::listarPorGrupo(
            (int)$grupoId,
            $pagina,
            $porPagina
        );

        $this->view('logs/index', [
            'logs'      => $resultado['logs'],
            'paginator' => $resultado['paginator'],
            'grupo_id'  => $grupoId
        ]);
    }
}