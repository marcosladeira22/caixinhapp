<?php

// Importa o controller base
require_once "../core/Controller.php";

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
