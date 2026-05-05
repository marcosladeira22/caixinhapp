<?php
namespace Controllers;

use Core\Controller;
use Core\Autenticacao;
use Core\Sessao;
use Services\UsuarioService;
use Exception;

/**
 * Controller responsável pelo perfil do usuário
 */
class UsuarioController extends Controller
{
    /**
     * Exibe perfil do usuário logado
     */
    public function perfil()
    {
        Autenticacao::exigirLogin();

        try {
            $usuario = UsuarioService::obterPerfil(
                Sessao::get('usuario_id')
            );

            $this->view('usuario/perfil', [
                'usuario' => $usuario
            ]);

        } catch (Exception $e) {
            $this->view('usuario/perfil', [
                'erro' => $e->getMessage()
            ]);
        }
    }

    /**
     * Atualiza dados do perfil
     */
    public function atualizar()
    {
        Autenticacao::exigirLogin();

        try {
            UsuarioService::atualizarPerfil(
                Sessao::get('usuario_id'),
                trim($_POST['nome']),
                trim($_POST['telefone']) ?: null,
                $_POST['sexo'],
                $_POST['senha'] ?? null
            );

            $this->redirect('?rota=usuario@perfil');

        } catch (Exception $e) {
            $this->view('usuario/perfil', [
                'erro' => $e->getMessage()
            ]);
        }
    }
}