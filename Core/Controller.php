<?php

// Define o namespace da classe
namespace Core;

// Classe base para todos os controllers
class Controller
{
    // Método responsável por carregar views
    protected function view($view, $data = [])
    {
        // Transforma o array em variáveis
        // ['title' => 'Exemplo'] vira $title
        extract($data);

        // Inclui o arquivo da view
        require "../App/Views/$view.php";
    }
}
