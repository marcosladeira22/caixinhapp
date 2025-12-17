<?php

// Define o namespace da classe
namespace App\Controllers;

// Importa o controller base
use Core\Controller;
use App\Models\User;

// Cria o controller Home
class HomeController extends Controller
{
    // Método padrão (ação index)
    public function index()
    {
        // Instancia o model
        $userModel = new User();

        // Busca usuários no banco
        $users = $userModel->getAll();

         // Envia os dados para a view
        $this->view('home', [
            'title' => 'Usuários cadastrados',
            'users' => $users
        ]);
        
        // Chama a view "home"
        // Envia dados para a view
        //$this->view('home', [
        //    'title' => 'Meu primeiro MVC em PHP'
        //]);
    }
}
