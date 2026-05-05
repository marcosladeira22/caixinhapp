<?php
namespace Controllers;

use Core\Controller;
use Core\Autenticacao;
use Core\Permissao;
use Services\AprovacaoEmprestimoService;
use Services\AprovacaoEmprestimoConsultaService;

/**
 * Controller responsável pela aprovação de empréstimos
 */
class AprovacaoEmprestimoController extends Controller
{
    /**
     * Lista empréstimos pendentes do grupo (ADMIN)
     */
    public function index()
    {
        Autenticacao::exigirLogin();

        $grupoId = $_GET['grupo_id'] ?? null;
        if (!$grupoId) {
            $this->redirect('?rota=dashboard@index');
        }

        Permissao::exigirAdmin((int)$grupoId);

        $emprestimos = AprovacaoEmprestimoConsultaService::listarPendentes(
            (int)$grupoId
        );

        $this->view('emprestimos/aprovacao', [
            'emprestimos' => $emprestimos,
            'grupo_id'    => $grupoId
        ]);
    }

    /**
     * Aprova empréstimo
     */
    public function aprovar()
    {
        Autenticacao::exigirLogin();

        $emprestimoId = $_POST['emprestimo_id'] ?? null;
        $grupoId      = $_POST['grupo_id'] ?? null;

        if (!$emprestimoId || !$grupoId) {
            $this->redirect('?rota=dashboard@index');
        }

        Permissao::exigirAdmin((int)$grupoId);

        AprovacaoEmprestimoService::aprovar((int)$emprestimoId);

        $this->redirect("?rota=aprovacaoEmprestimo@index&grupo_id={$grupoId}");
    }

    /**
     * Rejeita empréstimo
     */
    public function rejeitar()
    {
        Autenticacao::exigirLogin();

        $emprestimoId = $_POST['emprestimo_id'] ?? null;
        $grupoId      = $_POST['grupo_id'] ?? null;
        $motivo       = $_POST['motivo'] ?? null;

        if (!$emprestimoId || !$grupoId) {
            $this->redirect('?rota=dashboard@index');
        }

        Permissao::exigirAdmin((int)$grupoId);

        AprovacaoEmprestimoService::rejeitar(
            (int)$emprestimoId,
            $motivo
        );

        $this->redirect("?rota=aprovacaoEmprestimo@index&grupo_id={$grupoId}");
    }
}