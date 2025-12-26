<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\User;

/**
 * Controller responsável pelo CRUD de usuários
 */
class UserController extends Controller
{
    /**
     * Instância do model User
     */
    private $user;

    /**
     * Construtor
     */
    public function __construct()
    {
        // Executa o construtor do Controller base
        parent::__construct();

        // Instancia o model User
        $this->user = new User();
    }

    /**
     * LISTAGEM DE USUÁRIOS
     * Admin e Manager veem todos
     * User comum não deveria acessar (regra simples)
     */
    public function index()
    {
        // Apenas admin e manager
        if (!$this->hasRole(['admin', 'manager'])) {
            $this->setFlash('error', 'Acesso negado.');
            $this->redirect('/home/index');
        }

        // Quantidade por página
        $limit = 5;

        // Página atual (sempre >= 1)
        $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;

        // Offset do SQL
        $offset = ($page - 1) * $limit;

        // Busca (string)
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        // Ordenação
        $order = $_GET['order'] ?? 'id';
        $dir   = $_GET['dir'] ?? 'desc';

        // Se existir busca
        if ($search !== '') {

            // Busca paginada com filtro
            $users = $this->user->searchPaginatedOrdered(
                $search,
                $order,
                $dir,
                $limit,
                $offset
            );

            // Total filtrado
            $total = $this->user->countSearch($search);

        } else {

            // Busca paginada sem filtro
            $users = $this->user->getPaginatedOrdered(
                $order,
                $dir,
                $limit,
                $offset
            );

            // Total geral
            $total = $this->user->countAll();
        }

        // Total de páginas
        $totalPages = ceil($total / $limit);

        // Carrega a view
        $this->view('users/index', [
            'title'      => 'Usuários',
            'users'      => $users,
            'page'       => $page,
            'totalPages' => $totalPages,
            'search'     => $search,
            'order'      => $order,
            'dir'        => $dir
        ]);
    }

    /**
     * FORMULÁRIO DE CRIAÇÃO
     * Admin e Manager
     */
    public function create()
    {
        if (!$this->hasRole(['admin', 'manager'])) {
            $this->setFlash('error', 'Acesso negado.');
            $this->redirect('/user/index');
        }

        $this->view('users/create', [
            'title' => 'Novo usuário'
        ]);
    }

    /**
     * SALVA NOVO USUÁRIO
     */
    public function store()
    {
        // Apenas admin e manager
        if (!$this->hasRole(['admin', 'manager'])) {
            $this->setFlash('error', 'Acesso negado.');
            $this->redirect('/user/index');
        }

        // Valida CSRF
        if (!$this->validateCsrf()) {
            $this->setFlash('error', 'Token inválido.');
            $this->redirect('/user/create');
        }

        // Array de erros
        $errors = [];

        // Campos obrigatórios
        $errors = array_merge(
            $errors,
            $this->validateRequired([
                'name'     => 'Nome',
                'email'    => 'Email',
                'password' => 'Senha',
                'role'     => 'Perfil'
            ])
        );

        // Validação de email
        if (!empty($_POST['email']) && !$this->validateEmail($_POST['email'])) {
            $errors[] = 'Email inválido.';
        }

        // Email duplicado
        if ($this->user->emailExists($_POST['email'])) {
            $errors[] = 'Este email já está cadastrado.';
        }

        // Senha mínima
        if (!$this->validateMinLength($_POST['password'], 6)) {
            $errors[] = 'A senha deve ter no mínimo 6 caracteres.';
        }

        // Se houver erros
        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            $this->redirect('/user/create');
        }

        // Gera hash da senha
        $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Garante que role seja string
        $role = is_array($_POST['role']) ? $_POST['role'][0] : $_POST['role'];

        // Cria o usuário
        $userId = $this->user->create(
            $_POST['name'],
            $_POST['email'],
            $passwordHash,
            $role
        );

        // Upload de avatar (opcional)
        if (!empty($_FILES['avatar']['name'])) {
            $this->user->uploadAvatar($userId, $_FILES['avatar']);
        }

        // Log
        $this->log('create_user', "Usuário ID {$userId} criado");

        // Mensagem
        $this->setFlash('success', 'Usuário criado com sucesso.');

        // Redireciona
        $this->redirect('/user/index');
    }

    /**
     * FORMULÁRIO DE EDIÇÃO
     * Admin e Manager editam qualquer um
     * User edita apenas a si mesmo
     */
    public function edit($id)
    {
        $user = $this->user->find($id);

        if (!$user) {
            $this->setFlash('error', 'Usuário não encontrado.');
            $this->redirect('/user/index');
        }

        // Se não for admin/manager e não for o próprio usuário
        if (
            !$this->hasRole(['admin', 'manager']) &&
            $_SESSION['user']['id'] != $id
        ) {
            $this->setFlash('error', 'Acesso negado.');
            $this->redirect('/home/index');
        }

        $this->view('users/edit', [
            'title' => 'Editar usuário',
            'user'  => $user
        ]);
    }

    /**
     * SALVA EDIÇÃO
     */
    public function update($id)
    {
        $user = $this->user->find($id);

        if (!$user) {
            $this->setFlash('error', 'Usuário não encontrado.');
            $this->redirect('/user/index');
        }

        // Regra de acesso
        if (
            !$this->hasRole(['admin', 'manager']) &&
            $_SESSION['user']['id'] != $id
        ) {
            $this->setFlash('error', 'Acesso negado.');
            $this->redirect('/home/index');
        }

        $errors = [];

        // Senha opcional
        if (!empty($_POST['password']) && !$this->validateMinLength($_POST['password'], 6)) {
            $errors[] = 'A senha deve ter no mínimo 6 caracteres.';
        }

        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            $this->redirect("/user/edit/{$id}");
        }

        // Atualiza dados básicos
        // User comum NÃO altera role
        $role = $this->hasRole(['admin', 'manager'])
            ? $_POST['role']
            : $user['role'];

        $this->user->update(
            $id,
            $_POST['name'],
            $_POST['email'],
            $role
        );

        // Atualiza senha (se enviada)
        if (!empty($_POST['password'])) {
            $this->user->updatePassword(
                $id,
                password_hash($_POST['password'], PASSWORD_DEFAULT)
            );
        }

        // Atualiza avatar (se enviado)
        if (!empty($_FILES['avatar']['name'])) {
            $this->user->uploadAvatar($id, $_FILES['avatar']);
        }

        // Log
        $this->log('update_user', "Usuário ID {$id} atualizado");

        // Mensagem
        $this->setFlash('success', 'Usuário atualizado com sucesso.');

        // Redireciona
        $this->redirect('/user/index');
    }

    /**
     * SOFT DELETE
     */
    public function delete($id)
    {
        if (!$this->can('delete_user')) {
            $this->setFlash('error', 'Acesso negado.');
            $this->redirect('/user/index');
        }

        $user = $this->user->find($id);

        if (!$user) {
            $this->setFlash('error', 'Usuário não encontrado.');
            $this->redirect('/user/index');
        }

        if (
            $this->hasRole(['manager']) &&
            $user['role'] === 'admin'
        ) {
            $this->setFlash('error', 'Gerentes não podem excluir administradores.');
            $this->redirect('/user/index');
        }

        $this->user->softDelete($id);

        $this->log('delete_user', "Usuário ID {$id} desativado");

        $this->setFlash('success', 'Usuário desativado.');

        $this->redirect('/user/index');
    }

    /**
     * RESTAURA USUÁRIO
     */
    public function restore($id)
    {
        if (!$this->hasRole(['admin'])) {
            $this->setFlash('error', 'Acesso negado.');
            $this->redirect('/user/index');
        }

        $this->user->restore($id);

        $this->log('restore_user', "Usuário ID {$id} restaurado");

        $this->setFlash('success', 'Usuário restaurado.');

        $this->redirect('/user/index');
    }
}
