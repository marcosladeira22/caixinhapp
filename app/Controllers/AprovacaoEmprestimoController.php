<?php
namespace Controllers;

use Core\Controller;
use Core\Autenticacao;
use Core\Permissao;
use Services\AprovacaoEmprestimoService;
use Models\Emprestimo;

class AprovacaoEmprestimoController extends Controller
{
    /**
     * Lista empréstimos pendentes do grupo
     */
    public function index()
    {
        Autenticacao::verificar();

        $grupo_id = $_GET['grupo_id'] ?? null;

        if (!$grupo_id) {
            die('Grupo não informado.');
        }

        // 🔒 Apenas ADMIN
        Permissao::admin($grupo_id);

        $emprestimos = Emprestimo::listarPendentesPorGrupo($grupo_id);

        $this->view('emprestimos/aprovacao', compact('emprestimos', 'grupo_id'));
    }

    /**
     * Aprovar empréstimo
     */
    public function aprovar()
    {
        Autenticacao::verificar();

        $emprestimo_id = $_POST['emprestimo_id'];
        $grupo_id      = $_POST['grupo_id'];

        Permissao::admin($grupo_id);

        AprovacaoEmprestimoService::aprovar($emprestimo_id);

        header('Location: ' . base_url("?rota=aprovacaoEmprestimo@index&grupo_id={$grupo_id}"));
        exit;
    }

    /**
     * Rejeitar empréstimo
     */
    public function rejeitar()
    {
        Autenticacao::verificar();

        $emprestimo_id = $_POST['emprestimo_id'];
        $grupo_id      = $_POST['grupo_id'];

        Permissao::admin($grupo_id);

        AprovacaoEmprestimoService::rejeitar($emprestimo_id);

        header('Location: ' . base_url("?rota=aprovacaoEmprestimo@index&grupo_id={$grupo_id}"));
        exit;
    }
}