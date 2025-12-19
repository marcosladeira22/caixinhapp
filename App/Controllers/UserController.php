<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\User;

// Controller de usuários
class UserController extends Controller
{
    private $user;

    public function __construct()
    {
        // Construtor do Controller base
        parent::__construct();

        // Instancia o model User
        $this->user = new User();
    }

    // READ — lista usuários
    public function index()
    {
        $users = $this->user->getAll();

        $this->view('users/index', [
            'title' => 'Usuários',
            'users' => $users
        ]);
    }

    // CREATE — formulário
    public function create()
    {
        // Verifica se é administrador
        if (!$this->hasRole(['admin', 'manager'])) {

            // Mensagem de erro
            $this->setFlash('error', 'Acesso negado.');

            // Redireciona para listagem
            $this->redirect('/user/index');
        }
        
        $this->view('users/create', [
            'title' => 'Novo usuário'
        ]);
    }

    // CREATE — salva no banco
    public function store()
    {
        // Verifica se é administrador
        if (!$this->hasRole(['admin', 'manager'])) {

            // Mensagem de erro
            $this->setFlash('error', 'Acesso negado.');

            // Redireciona para listagem
            $this->redirect('/user/index');
        }

        // 1 - Valida CSRF
        if (!$this->validateCsrf()) {
            $this->setFlash('error', 'Token inválido. Recarregue o formulário.');
            $this->redirect('/user/create');
        }

        // 2️ - Campos obrigatórios
        $errors = $this->validateRequired([
            'name'     => 'Nome',
            'email'    => 'Email',
            'password' => 'Senha'
        ]);

        // 3️ - Validação de formato de email
        if (!empty($_POST['email']) && !$this->validateEmail($_POST['email'])) {
            $errors[] = 'O email informado não é válido.';
        }

        // 4️ - Verifica se email já existe
        if (!empty($_POST['email']) && $this->user->emailExists($_POST['email'])) {
            $errors[] = 'Este email já está cadastrado.';
        }

        // 5️ - Validação de tamanho mínimo da senha
        if (!empty($_POST['password']) && !$this->validateMinLength($_POST['password'], 6)) {
            $errors[] = 'A senha deve ter no mínimo 6 caracteres.';
        }

        // 6️ - Se existir qualquer erro
        if (!empty($errors)) {

            // Salva todos os erros juntos
            $this->setFlash('error', implode('<br>', $errors));

            // Volta para o formulário
            $this->redirect('/user/create');
        }

        // 7️ - Gera hash seguro da senha
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // 8️ - Salva no banco
        $this->user->create(
            $_POST['name'],
            $_POST['email'],
            $password
        );

        // Log de criação
        $this->log('create_user', 'Usuário criou um novo cadastro');
        
        // 9️ - Mensagem de sucesso
        $this->setFlash('success', 'Usuário cadastrado com sucesso.');

        // 10 - Redireciona para listagem
        $this->redirect('/user/index');
    }

    // UPDATE — formulário
    public function edit($id)
    {
        // Verifica se é administrador
        if (!$this->hasRole(['admin', 'manager'])) {

            // Mensagem de erro
            $this->setFlash('error', 'Acesso negado.');

            // Redireciona para listagem
            $this->redirect('/user/index');
        }

        $user = $this->user->find($id);

        $this->view('users/edit', [
            'title' => 'Editar usuário',
            'user'  => $user
        ]);
    }

    // UPDATE — salva edição
    public function update($id)
    {
        // Verifica se é administrador
        if (!$this->hasRole(['admin', 'manager'])) {

            // Mensagem de erro
            $this->setFlash('error', 'Acesso negado.');

            // Redireciona para listagem
            $this->redirect('/user/index');
        }

        $this->user->update(
            $id,
            $_POST['name'],
            $_POST['email']
        );

        // Log de edição
        $this->log('update_user', "Usuário editou o cadastro ID {$id}");

        //Redirecionamento
        $this->redirect('/user/index');
    }

    // DELETE — remove
        public function delete($id)
    {
        // 1️ Verifica se o usuário logado é admin ou manager
        if (!$this->hasRole(['admin', 'manager'])) {

            // Mensagem de erro
            $this->setFlash('error', 'Acesso negado.');

            // Redireciona para listagem
            $this->redirect('/user/index');
        }

        // 2️ Busca o usuário que será excluído
        $userToDelete = $this->user->find($id);

        // 3️ Se o usuário não existir
        if (!$userToDelete) {

            $this->setFlash('error', 'Usuário não encontrado.');
            $this->redirect('/user/index');
        }

        // 4️ Se quem está logado for manager
        //     e o usuário alvo for admin
        if (
            $this->hasRole(['manager']) &&
            $userToDelete['role'] === 'admin'
        ) {
            // Bloqueia a ação
            $this->setFlash(
                'error',
                'Gerentes não podem excluir administradores.'
            );

            $this->redirect('/user/index');
        }

        // 5️ Se passou por todas as regras, pode excluir
        $this->user->delete($id);

        // 6️ Registra log da exclusão
        $this->log('delete_user', "Usuário excluiu o cadastro ID {$id}");

        // 7️ Mensagem de sucesso
        $this->setFlash('success', 'Usuário excluído com sucesso.');

        // 8️ Redireciona
        $this->redirect('/user/index');
    }

}
