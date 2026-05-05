<?php
namespace Controllers;

use Core\Controller;
use Core\Autenticacao;
use Core\Permissao;
use Services\RelatorioFinanceiroService;

/**
 * Controller responsável pelos relatórios do sistema
 */
class RelatorioController extends Controller
{
    /**
     * Relatório financeiro do grupo
     */
    public function financeiro()
    {
        Autenticacao::exigirLogin();

        $grupoId = $_GET['grupo_id'] ?? null;
        if (!$grupoId) {
            $this->redirect('?rota=dashboard@index');
        }

        Permissao::exigirAdmin((int)$grupoId);

        $dados = RelatorioFinanceiroService::gerar((int)$grupoId);

        $this->view('relatorios/financeiro', [
            'grupo_id' => $grupoId,
            'dados'    => $dados
        ]);
    }
}