<?php
namespace Controllers;

use Core\Controller;
use Core\Autenticacao;
use Core\Permissao;
use Services\UsuarioGrupoService;
use Core\Sessao;
use Exception;

/**
 * Controller responsável pela gestão de usuários do grupo
 */
class UsuarioGrupoController extends Controller
{
    public function criar()
    {
        Autenticacao::exigirLogin();

        $grupoId = $_GET['grupo_id'] ?? null;
        if (!$grupoId) {
            $this->redirect('?rota=dashboard@index');
        }

        Permissao::exigirAdmin((int)$grupoId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            try {
                UsuarioGrupoService::adicionarAoGrupo(
                    (int)$grupoId,
                    trim($_POST['nome']),
                    filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL),
                    trim($_POST['telefone']) ?: null,
                    $_POST['sexo'] ?? 'O',
                    $_POST['nivel'],
                    (int)$_POST['quantidade_cotas']
                );

                $this->redirect("?rota=usuarioGrupo@index&grupo_id={$grupoId}");

            } catch (Exception $e) {
                $this->view('usuarios_grupo/criar', [
                    'erro'     => $e->getMessage(),
                    'grupo_id' => $grupoId
                ]);
            }

            return;
        }

        $this->view('usuarios_grupo/criar', [
            'grupo_id' => $grupoId
        ]);
    }

    public function index()
    {
        Autenticacao::exigirLogin();

        $grupoId = $_GET['grupo_id'] ?? null;
        if (!$grupoId) {
            $this->redirect('?rota=dashboard@index');
        }

        Permissao::exigirAdmin((int)$grupoId);

        $pagina = (int)($_GET['page'] ?? 1);
        $porPagina = (int)($_GET['per_page'] ?? 10);

        $resultado = UsuarioGrupoService::listar(
            (int)$grupoId,
            $pagina,
            $porPagina
        );

        $this->view('usuarios_grupo/index', [
            'usuarios'  => $resultado['usuarios'],
            'paginator' => $resultado['paginator'],
            'grupo_id'  => $grupoId
        ]);
    }

    public function editar()
    {
        Autenticacao::exigirLogin();

        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('?rota=dashboard@index');
        }

        $this->view('usuarios_grupo/editar', [
            'registro' => \Models\GrupoUsuario::buscarPorId((int)$id)
        ]);
    }

    public function atualizar()
    {
        Autenticacao::exigirLogin();

        try {
            UsuarioGrupoService::atualizarVinculo(
                (int)$_POST['id'],
                (int)$_POST['quantidade_cotas'],
                $_POST['nivel'],
                isset($_POST['ativo'])
            );

            $this->redirect('?rota=dashboard@index');

        } catch (Exception $e) {
            $this->view('usuarios_grupo/editar', [
                'erro' => $e->getMessage(),
                'registro' => \Models\GrupoUsuario::buscarPorId((int)$_POST['id'])
            ]);
        }
    }
}