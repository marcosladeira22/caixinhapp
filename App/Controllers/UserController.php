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
        $this->view('users/create', [
            'title' => 'Novo usuário'
        ]);
    }

    // CREATE — salva no banco
    public function store()
    {
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

        // 9️ - Mensagem de sucesso
        $this->setFlash('success', 'Usuário cadastrado com sucesso.');

        // 10 - Redireciona para listagem
        $this->redirect('/user/index');
    }

    // UPDATE — formulário
    public function edit($id)
    {
        $user = $this->user->find($id);

        $this->view('users/edit', [
            'title' => 'Editar usuário',
            'user'  => $user
        ]);
    }

    // UPDATE — salva edição
    public function update($id)
    {
        $this->user->update(
            $id,
            $_POST['name'],
            $_POST['email']
        );

        //Redirecionamento
        $this->redirect('/user/index');
    }

    // DELETE — remove
    public function delete($id)
    {
        $this->user->delete($id);

        //Redirecionamento
        $this->redirect('/user/index');
    }
}
