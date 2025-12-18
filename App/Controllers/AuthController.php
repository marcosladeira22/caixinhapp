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
        // Verifica se os dados vieram do formulário
        if (!isset($_POST['email'], $_POST['password'])) {
            
            // Mensagem de erro (acesso inválido)
            $this->setFlash('error', 'Acesso inválido.');

            // Volta para o login
            $this->redirect('/auth/index');
        }

        // Instancia o model User
        $user = new User();

        // Busca usuário pelo e-mail
        $data = $user->findByEmail($_POST['email']);

        // Se usuário NÃO existe ou senha está incorreta
        if (!$data || !password_verify($_POST['password'], $data['password'])) {

            // Mensagem de erro de autenticação
            $this->setFlash('error', 'Email ou senha inválidos.');

            // Volta para o login
            $this->redirect('/auth/index');
        }

        // Se chegou aqui, login é válido

        // Salva dados básicos do usuário na sessão
        $_SESSION['user'] = [
            'id'    => $data['id'],
            'name'  => $data['name'],
            'email' => $data['email']
        ];

        // Mensagem de sucesso
        $this->setFlash('success', 'Bem-vindo, ' . $data['name'] . '!');

        // Redireciona para lista de usuários
        $this->redirect('/user/index');
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
