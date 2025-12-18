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
        parent::__construct();
        
        // Instancia o model
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
        $this->user->create(
            $_POST['name'],
            $_POST['email']
        );

        //Redirecionamento
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
