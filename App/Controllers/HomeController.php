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
        // Chama a view "home"
        // Envia dados para a view
        $this->view('home', [
            'title' => 'Meu primeiro MVC em PHP'
        ]);
    }
}
