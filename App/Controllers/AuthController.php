<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\User;

// Controller responsável pela autenticação
class AuthController extends Controller
{
    // Exibe a tela de login
    public function index()
    {
        // Carrega a view do login
        $this->view('auth/login', [
            'title' => 'Login'
        ]);
    }

    // Processa o login (POST)
    public function login()
    {
        //die('Cheguei no login');
        // Verifica se os dados vieram do formulário
        if (!isset($_POST['email'], $_POST['password'])) {
            $this->redirect('/auth/index');
        }

        // Instancia o model User
        $user = new User();

        // Busca usuário pelo e-mail
        $data = $user->findByEmail($_POST['email']);

        // Verifica se usuário existe e se a senha confere
        if ($data && password_verify($_POST['password'], $data['password'])) {

            // Salva dados básicos na sessão
            $_SESSION['user'] = [
                'id'   => $data['id'],
                'name' => $data['name'],
                'email'=> $data['email']
            ];

            // Redireciona para lista de usuários
            $this->redirect('/user/index');
        }

        // Se falhar, volta para login
        $this->redirect('/auth/index');
    }

    // Faz logout
    public function logout()
    {
        // Remove dados do usuário da sessão
        unset($_SESSION['user']);

        // Destrói a sessão
        session_destroy();

        // Redireciona para login
        $this->redirect('/auth/index');
    }
}
