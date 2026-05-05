<?php
namespace Controllers;

use Core\Controller;
use Core\Autenticacao;
use Core\Sessao;
use Services\GrupoService;
use Exception;

/**
 * Controller responsável pelos grupos
 */
class GrupoController extends Controller
{
    /**
     * Lista grupos do usuário
     */
    public function index()
    {
        Autenticacao::exigirLogin();

        $usuarioId = Sessao::get('usuario_id');

        $grupos = GrupoService::listarDoUsuario($usuarioId);

        $this->view('grupos/index', [
            'grupos' => $grupos
        ]);
    }

    /**
     * Criação de novo grupo
     */
    public function criar()
    {
        Autenticacao::exigirLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            try {
                $grupoId = GrupoService::criarGrupo([
                    'nome'             => $_POST['nome'],
                    'valor_cota'       => $_POST['valor_cota'],
                    'emprestimo_min'   => $_POST['emprestimo_min'],
                    'emprestimo_max'   => $_POST['emprestimo_max'],
                    'taxa_tipo'        => $_POST['taxa_tipo'],
                    'taxa_valor'       => $_POST['taxa_valor'],
                    'juros_tipo'       => $_POST['juros_tipo'],
                    'juros_valor'      => $_POST['juros_valor'],
                    'dias_tolerancia'  => $_POST['dias_tolerancia'],
                ]);

                $this->redirect("?rota=dashboard@grupo&grupo_id={$grupoId}");

            } catch (Exception $e) {
                $this->view('grupos/criar', [
                    'erro' => $e->getMessage()
                ]);
            }

            return;
        }

        $this->view('grupos/criar');
    }
}