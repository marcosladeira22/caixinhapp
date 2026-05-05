<?php
namespace Controllers;

use Core\Controller;
use Core\Autenticacao;
use Core\Sessao;
use Services\EmprestimoService;
use Services\EmprestimoConsultaService;
use Exception;

/**
 * Controller responsável pelas ações de empréstimo
 */
class EmprestimoController extends Controller
{
    /**
     * Lista empréstimos do grupo
     */
    public function index()
    {
        Autenticacao::exigirLogin();

        $grupoId = $_GET['grupo_id'] ?? null;
        if (!$grupoId) {
            $this->redirect('?rota=dashboard@index');
        }

        $pagina = (int)($_GET['page'] ?? 1);
        $porPagina = (int)($_GET['per_page'] ?? 10);

        $resultado = EmprestimoConsultaService::listarPorGrupo(
            (int)$grupoId,
            $pagina,
            $porPagina
        );

        $this->view('emprestimos/index', [
            'emprestimos' => $resultado['emprestimos'],
            'paginator'   => $resultado['paginator'],
            'grupo_id'    => $grupoId
        ]);
    }

    /**
     * Solicitação de empréstimo
     */
    public function solicitar()
    {
        Autenticacao::exigirLogin();

        $grupoId   = $_GET['grupo_id'] ?? null;
        $usuarioId = Sessao::get('usuario_id');

        if (!$grupoId) {
            $this->redirect('?rota=dashboard@index');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            try {
                EmprestimoService::solicitar(
                    $usuarioId,
                    (int)$grupoId,
                    (float)$_POST['valor']
                );

                $this->redirect("?rota=dashboard@grupo&grupo_id={$grupoId}");

            } catch (Exception $e) {
                $this->view('emprestimos/solicitar', [
                    'grupo_id' => $grupoId,
                    'erro'     => $e->getMessage()
                ]);
            }

            return;
        }

        $this->view('emprestimos/solicitar', [
            'grupo_id' => $grupoId
        ]);
    }

    /**
     * Lista inadimplentes do grupo
     */
    public function inadimplentes()
    {
        Autenticacao::exigirLogin();

        $grupoId = $_GET['grupo_id'] ?? null;
        if (!$grupoId) {
            $this->redirect('?rota=dashboard@index');
        }

        $lista = EmprestimoConsultaService::listarInadimplentes(
            (int)$grupoId
        );

        $this->view('emprestimos/inadimplentes', [
            'lista'    => $lista,
            'grupo_id' => $grupoId
        ]);
    }
}