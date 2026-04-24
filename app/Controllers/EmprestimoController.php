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
        if (!$grupo_id) die('Grupo não informado');

        $emprestimos = \Models\Emprestimo::listarPorGrupo($grupo_id);

        $this->view('emprestimos/index', compact('emprestimos', 'grupo_id'));
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