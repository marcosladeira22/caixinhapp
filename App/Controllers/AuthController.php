<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\User;
use App\Models\Permission;


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
        // Valida o token CSRF
        if (!$this->validateCsrf()) {

            // Mensagem de erro
            $this->setFlash('error', 'Token inválido. Tente novamente.');

            // Volta para o login
            $this->redirect('/auth/index');
        }
        
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

        // Busca usuário (ativo ou não)
        $data = $user->findByEmailIncludingInactive($_POST['email']);

        // Se usuário não existir
        if (!$data) {

            $this->setFlash('error', 'E-mail ou senha inválidos.');
            $this->redirect('/auth/index');
        }

        // Se usuário estiver desativado
        if ($data['deleted_at'] !== null) {

            $this->setFlash(
                'error',
                'Seu usuário está desativado. Entre em contato com o administrador.'
            );

            $this->redirect('/auth/index');
        }

        // Se senha estiver incorreta
        if (!password_verify($_POST['password'], $data['password'])) {

            $this->setFlash('error', 'E-mail ou senha inválidos.');
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
            'email' => $data['email'],
            'role'  => $data['role'],
            'avatar' => $data['avatar'] ?? null
        ];

        // Carrega permissões do role
        $permissionModel = new Permission();

        $_SESSION['permissions'] = $permissionModel->getPermissionsByRole($data['role']);

        // Registra log de login
        $this->log('login', 'Usuário realizou login no sistema');

        // Mensagem de sucesso
        $this->setFlash('success', 'Bem-vindo, ' . $data['name'] . '!');

        // Redireciona para a área protegida
        $this->redirect('/user/index');
    }


    // Faz logout
    public function logout()
    {
        $this->log('logout','Usuário saiu do sistema');
        
        // Remove dados do usuário da sessão
        unset($_SESSION['user']);
        unset($_SESSION['permissions']);

        // Destrói a sessão
        session_destroy();

        // Redireciona para login
        $this->redirect('/auth/index');
    }
}
