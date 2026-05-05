<?php
namespace Controllers;

use Core\Controller;
use Core\Autenticacao;
use Core\Permissao;
use Services\FechamentoService;
use Exception;

/**
 * Controller responsável pelo fechamento do grupo
 */
class FechamentoController extends Controller
{
    /**
     * Tela de resumo antes do fechamento
     */
    public function resumo()
    {
        Autenticacao::exigirLogin();

        $grupoId = $_GET['grupo_id'] ?? null;
        if (!$grupoId) {
            $this->redirect('?rota=dashboard@index');
        }

        Permissao::exigirAdmin((int)$grupoId);

        $this->view('fechamento/resumo', [
            'grupo_id' => $grupoId
        ]);
    }

    /**
     * Executa o fechamento do grupo
     */
    public function fechar()
    {
        Autenticacao::exigirLogin();

        $grupoId = $_POST['grupo_id'] ?? null;
        if (!$grupoId) {
            $this->redirect('?rota=dashboard@index');
        }

        Permissao::exigirAdmin((int)$grupoId);

        try {
            FechamentoService::fecharGrupo((int)$grupoId);

            $this->redirect('?rota=dashboard@index');

        } catch (Exception $e) {
            $this->view('fechamento/resumo', [
                'grupo_id' => $grupoId,
                'erro'     => $e->getMessage()
            ]);
        }
    }
}