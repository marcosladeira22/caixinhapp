<?php
namespace Controllers;

use Core\Controller;
use Core\Autenticacao;
use Core\Permissao;
use Services\FechamentoService;

class FechamentoController extends Controller
{
    public function resumo()
    {
        Autenticacao::verificar();

        $grupo_id = $_GET['grupo_id'] ?? null;
        if (!$grupo_id) die('Grupo não informado');

        Permissao::admin($grupo_id);

        $this->view('fechamento/resumo', compact('grupo_id'));
    }

    public function fechar()
    {
        Autenticacao::verificar();

        $grupo_id = $_POST['grupo_id'];

        Permissao::admin($grupo_id);

        FechamentoService::fecharGrupo($grupo_id);

        header('Location: ' . base_url('?rota=dashboard@index'));
        exit;
    }
}