<?php
namespace Controllers;

use Core\Controller;
use Core\Autenticacao;
use Core\Sessao;
use Services\EmprestimoService;
use Exception;

class EmprestimoController extends Controller
{

    /**
     * 
     */
    public function index()
    {
        \Core\Autenticacao::verificar();

        $grupo_id = $_GET['grupo_id'] ?? null;
        if (!$grupo_id) {
            die('Grupo não informado.');
        }

        // Paginação
        $paginaAtual = (int)($_GET['page'] ?? 1);
        $porPagina   = (int)($_GET['per_page'] ?? 10);

        // Total
        $total = \Models\Emprestimo::contarPorGrupo($grupo_id);

        $paginator = new \Core\Paginator(
            $total,
            $paginaAtual,
            $porPagina
        );

        $emprestimos = \Models\Emprestimo::listarPorGrupoPaginado(
            $grupo_id,
            $paginator->porPagina,
            $paginator->offset
        );

        $this->view('emprestimos/index', [
            'emprestimos' => $emprestimos,
            'grupo_id'    => $grupo_id,
            'paginator'   => $paginator
        ]);
    }

    /**
     * Solicitação de empréstimo (membro ou admin)
     */
    public function solicitar()
    {
        Autenticacao::verificar();

        $grupo_id   = $_GET['grupo_id'] ?? null;
        $usuario_id = Sessao::get('usuario_id');

        if (!$grupo_id) {
            die('Grupo não informado.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $valor = (float) $_POST['valor'];

            try {
                EmprestimoService::solicitar($usuario_id, $grupo_id, $valor);

                header('Location: ' . base_url("?rota=dashboard@grupo&grupo_id={$grupo_id}"));
                exit;

            } catch (Exception $e) {
                $erro = $e->getMessage();
            }
        }

        $this->view('emprestimos/solicitar', compact('grupo_id', 'erro'));
    }

    /**
     * 
     */
    public function inadimplentes()
    {
        \Core\Autenticacao::verificar();

        $grupo_id = $_GET['grupo_id'] ?? null;
        if (!$grupo_id) die('Grupo não informado');

        $lista = \Models\Emprestimo::listarPorStatus($grupo_id, 'ATRASADO');

        $this->view('emprestimos/inadimplentes', compact('lista', 'grupo_id'));
    }
}