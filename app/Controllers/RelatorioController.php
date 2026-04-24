<?php
namespace Controllers;

use Core\Controller;
use Core\Autenticacao;
use Core\Permissao;
use Services\RelatorioFinanceiroService;

class RelatorioController extends Controller
{
    /**
     * Relatório financeiro do grupo
     */
    public function financeiro()
    {
        Autenticacao::verificar();

        $grupo_id = $_GET['grupo_id'] ?? null;
        if (!$grupo_id) {
            die('Grupo não informado.');
        }

        // 🔒 Apenas ADMIN
        Permissao::admin($grupo_id);

        $dados = RelatorioFinanceiroService::gerar($grupo_id);

        $this->view('relatorios/financeiro', [
            'grupo_id' => $grupo_id,
            'dados'    => $dados
        ]);
    }
}