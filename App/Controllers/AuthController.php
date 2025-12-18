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
        /*
        |--------------------------------------------------------------------------
        | VALIDAÇÃO DOS CAMPOS OBRIGATÓRIOS
        |--------------------------------------------------------------------------
        | Verifica se email e senha foram preenchidos
        */

        $errors = $this->validateRequired([
            'email'    => 'Email',
            'password' => 'Senha'
        ]);

        // Se existir erro de validação
        if (!empty($errors)) {

            // Salva mensagem de erro na sessão
            $this->setFlash('error', implode('<br>', $errors));

            // Volta para a tela de login
            $this->redirect('/auth/index');
        }

        /*
        |--------------------------------------------------------------------------
        | AUTENTICAÇÃO
        |--------------------------------------------------------------------------
        */

        // Instancia o model User
        $user = new User();

        // Busca o usuário pelo email informado
        $data = $user->findByEmail($_POST['email']);

        // Se usuário não existir ou senha estiver incorreta
        if (!$data || !password_verify($_POST['password'], $data['password'])) {

            // Mensagem de erro
            $this->setFlash('error', 'Email ou senha inválidos.');

            // Volta para o login
            $this->redirect('/auth/index');
        }

        /*
        |--------------------------------------------------------------------------
        | LOGIN BEM-SUCEDIDO
        |--------------------------------------------------------------------------
        */

        // Salva dados do usuário na sessão
        $_SESSION['user'] = [
            'id'    => $data['id'],
            'name'  => $data['name'],
            'email' => $data['email']
        ];

        // Mensagem de sucesso
        $this->setFlash('success', 'Bem-vindo, ' . $data['name'] . '!');

        // Redireciona para a área protegida
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
