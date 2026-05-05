<?php
namespace Controllers;

use Core\Controller;
use Core\Sessao;
use Services\AuthService;
use Exception;

/**
 * Controller responsável por autenticação
 */
class AuthController extends Controller
{
    /**
     * Login de usuário
     */
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $senha = $_POST['senha'] ?? null;

            if (!$email || !$senha) {
                $this->view('auth/login', [
                    'erro' => 'Dados inválidos.'
                ]);
                return;
            }

            try {
                AuthService::login($email, $senha);
                $this->redirect('?rota=dashboard@index');
            } catch (Exception $e) {
                $this->view('auth/login', [
                    'erro' => $e->getMessage()
                ]);
            }

            return;
        }

        $this->view('auth/login');
    }

    /**
     * Cadastro de novo usuário
     */
    public function cadastro()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $nome     = trim($_POST['nome'] ?? '');
            $email    = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $senha    = $_POST['senha'] ?? '';
            $telefone = trim($_POST['telefone'] ?? '') ?: null;
            $sexo     = $_POST['sexo'] ?? 'O';

            if (!$nome || !$email || !$senha) {
                $this->view('auth/cadastro', [
                    'erro' => 'Preencha todos os campos obrigatórios.'
                ]);
                return;
            }

            try {
                $usuarioId = AuthService::cadastrar(
                    $nome,
                    $email,
                    $senha,
                    $telefone,
                    $sexo
                );

                Sessao::set('usuario_id', $usuarioId);
                Sessao::set('usuario_nome', $nome);

                $this->redirect('?rota=grupo@criar');

            } catch (Exception $e) {
                $this->view('auth/cadastro', [
                    'erro' => $e->getMessage()
                ]);
            }

            return;
        }

        $this->view('auth/cadastro');
    }

    /**
     * Logout do sistema
     */
    public function logout()
    {
        Sessao::destruir();
        $this->redirect('?rota=auth@login');
    }
}