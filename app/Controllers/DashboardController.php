<?php
namespace Controllers;

use Core\Controller;
use Core\Autenticacao;
use Core\Sessao;
use Services\DashboardService;
use Exception;

/**
 * Controller responsável pelo fluxo de dashboard
 */
class DashboardController extends Controller
{
    /**
     * Fluxo inicial após login
     */
    public function index()
    {
        Autenticacao::exigirLogin();

        $usuarioId = Sessao::get('usuario_id');
        $grupos = DashboardService::gruposDoUsuario($usuarioId);

        // Nenhum grupo → criar primeiro
        if (empty($grupos)) {
            $this->redirect('?rota=grupo@criar');
        }

        // Apenas um grupo → entra direto
        if (count($grupos) === 1) {
            $grupoId = $grupos[0]['id'];
            $this->redirect("?rota=dashboard@grupo&grupo_id={$grupoId}");
        }

        // Vários grupos → escolhe
        $this->view('dashboard/selecionar_grupo', [
            'grupos' => $grupos
        ]);
    }

    /**
     * Dashboard de um grupo específico
     */
    public function grupo()
    {
        Autenticacao::exigirLogin();

        $grupoId = $_GET['grupo_id'] ?? null;
        if (!$grupoId) {
            $this->redirect('?rota=dashboard@index');
        }

        $usuarioId = Sessao::get('usuario_id');

        try {
            $dados = DashboardService::dadosDoGrupo(
                $usuarioId,
                (int)$grupoId
            );

            $this->view('dashboard/index', [
                'dados' => $dados
            ]);

        } catch (Exception $e) {
            $this->view('dashboard/index', [
                'erro' => $e->getMessage()
            ]);
        }
    }
}
