<?php
namespace Controllers;

use Core\Controller;
use Core\Sessao;
use Models\Usuario;

class AuthController extends Controller
{
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $senha = $_POST['senha'];

            if (!$email || !$senha) {
                $this->view('auth/login', ['erro' => 'Dados inválidos']);
                return;
            }

            $usuario = Usuario::buscarPorEmail($email);

            if (!$usuario || !password_verify($senha, $usuario['senha'])) {
                $this->view('auth/login', ['erro' => 'E-mail ou senha incorretos']);
                return;
            }

            Sessao::set('usuario_id', $usuario['id']);
            Sessao::set('usuario_nome', $usuario['nome']);

            header('Location: /dashboard.php');
            exit;
        }

        $this->view('auth/login');
    }
}