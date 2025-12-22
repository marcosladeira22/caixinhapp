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
        // Quantidade por página
        $limit = 5;

        // Página atual
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        if ($page < 1) {
            $page = 1;
        }

        // Offset
        $offset = ($page - 1) * $limit;

        // Busca (sempre string)
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        // Ordenação
        $order = $_GET['order'] ?? 'id';
        $dir   = $_GET['dir'] ?? 'desc';

        // Com busca
        if ($search !== '') {

            $users = $this->user->searchPaginatedOrdered(
                $search,
                $order,
                $dir,
                $limit,
                $offset
            );

            $total = $this->user->countSearch($search);

        } else {

            $users = $this->user->getPaginatedOrdered(
                $order,
                $dir,
                $limit,
                $offset
            );

            $total = $this->user->countAll();
        }

        // Total de páginas
        $totalPages = ceil($total / $limit);

        // View
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
        // Verifica se é administrador ou gerente
        if (!$this->hasRole(['admin', 'manager'])) {

            // Mensagem de erro
            $this->setFlash('error', 'Acesso negado.');

            // Redireciona para listagem
            $this->redirect('/user/index');
        }

        // Busca o usuário que será editado
        $userToDelete = $this->user->find($id);

        // Se quem está logado for manager
        // e o usuário alvo for admin → bloqueia
        if (
            $this->hasRole(['manager']) &&
            $userToDelete['role'] === 'admin'
        ) {

            // Mensagem explicativa
            $this->setFlash(
                'error',
                'Gerentes não podem editar administradores.'
            );

            // Redireciona
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
        // 1️ - Verifica se o usuário logado é admin ou manager
        // Se não for, bloqueia o acesso
        if (!$this->hasRole(['admin', 'manager'])) {

            // Define mensagem de erro
            $this->setFlash('error', 'Acesso negado.');

            // Redireciona para listagem de usuários
            $this->redirect('/user/index');
        }

        // 2️ - Busca o usuário que será "excluído"
        // Usamos find para verificar se ele existe
        $userToDelete = $this->user->find($id);

        // 3️ - Se o usuário não existir no banco
        if (!$userToDelete) {

            // Mensagem de erro
            $this->setFlash('error', 'Usuário não encontrado.');

            // Redireciona para listagem
            $this->redirect('/user/index');
        }

        // 4️ - Regra especial:
        // Se quem está logado for manager
        // e o usuário alvo for admin → bloqueia
        if (
            $this->hasRole(['manager']) &&
            $userToDelete['role'] === 'admin'
        ) {

            // Mensagem explicativa
            $this->setFlash(
                'error',
                'Gerentes não podem excluir administradores.'
            );

            // Redireciona
            $this->redirect('/user/index');
        }

        // 5️ - AQUI ESTÁ A ÚNICA MUDANÇA IMPORTANTE
        // Em vez de apagar do banco, fazemos SOFT DELETE
        // Isso apenas preenche a coluna deleted_at
        $this->user->softDelete($id);

        // 6️ - Registra a ação no log de auditoria
        $this->log(
            'delete_user',
            "Usuário ID {$id} foi desativado (soft delete)"
        );

        // 7️ - Mensagem de sucesso para o usuário
        $this->setFlash('success', 'Usuário desativado com sucesso.');

        // 8️ - Redireciona para listagem
        $this->redirect('/user/index');
    }

    // Restaurar usuário
    public function restore($id)
        {
        // 1️ - Apenas administradores podem restaurar usuários
        if (!$this->hasRole(['admin'])) {

            // Mensagem de erro
            $this->setFlash('error', 'Acesso negado.');

            // Redireciona
            $this->redirect('/user/index');
        }

        // 2️ - Restaura o usuário (remove deleted_at)
        $this->user->restore($id);

        // 3️ - Registra log de auditoria
        // Usa o MESMO padrão já existente no projeto
        $this->log(
            'restore_user',
            "Usuário ID {$id} foi reativado"
        );

        // 4️ - Mensagem de sucesso
        $this->setFlash('success', 'Usuário restaurado com sucesso.');

        // 5️ - Redireciona para listagem
        $this->redirect('/user/index');
    }

    public function deleted()
    {
        // 1️ - Apenas administradores podem acessar
        if (!$this->hasRole(['admin'])) {

            // Mensagem de erro
            $this->setFlash('error', 'Acesso negado.');

            // Redireciona para usuários ativos
            $this->redirect('/user/index');
        }

        // 2️ - Busca usuários desativados no model
        $users = $this->user->getDeleted();

        // 3️ - Carrega a view
        $this->view('users/deleted', [
            'title' => 'Usuários desativados',
            'users' => $users
        ]);
    }

    public function avatar()
    {
        // Apenas usuário logado
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login/index');
        }

        // Carrega view
        $this->view('users/avatar', [
            'title' => 'Alterar avatar'
        ]);
    }

    public function uploadAvatar()
    {
        // Verifica se usuário está logado
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login/index');
        }

        // Verifica se arquivo foi enviado
        if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== 0) {
            $this->setFlash('error', 'Erro ao enviar arquivo.');
            $this->redirect('/user/avatar');
        }

        // Tipos permitidos
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

        // Verifica tipo do arquivo
        if (!in_array($_FILES['avatar']['type'], $allowedTypes)) {
            $this->setFlash('error', 'Formato de imagem inválido.');
            $this->redirect('/user/avatar');
        }

        // Gera nome único para o arquivo
        $extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;

        // Caminho de destino
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/caixinhapp/public/uploads/avatars/';

        // Garante que o diretório existe
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Caminho final do arquivo
        $destination = $uploadDir . $filename;
        
        // Move o arquivo
        if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $destination)) {

            // Se falhar, aborta
            $this->setFlash('error', 'Falha ao salvar o arquivo.');
            $this->redirect('/user/avatar');
        }

        // Atualiza no banco
        $this->user->updateAvatar($_SESSION['user']['id'], $filename);

        // Atualiza sessão
        $_SESSION['user']['avatar'] = $filename;

        // Log da ação
        $this->log('update_avatar', 'Avatar atualizado');

        // Mensagem de sucesso
        $this->setFlash('success', 'Avatar atualizado com sucesso.');

        // Redireciona
        $this->redirect('/user/avatar');
    }


}
