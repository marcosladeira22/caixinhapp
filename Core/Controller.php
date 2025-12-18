<?php

// Define o namespace da classe
namespace Core;

// Classe base para todos os controllers
class Controller
{
    protected $base_url;

    public function __construct()
    {
        // Carrega config
        $config = require __DIR__ . '/../config/app.php';

        // Disponibiliza base_url para o controller
        $this->base_url = $config['base_url'];
    }

    // Método responsável por carregar views
    protected function view($view, $data = [])
    {
         // Disponibiliza base_url para as views
        $data['base_url'] = $this->base_url;

        // Transforma o array em variáveis
        // ['title' => 'Exemplo'] vira $title
        extract($data);

        // Inclui o arquivo da view
        require __DIR__ . "/../App/Views/$view.php";
    }

    // 🔴 NOVO: método padrão de redirecionamento
    protected function redirect($path)
    {
        header("Location: {$this->base_url}{$path}");
        exit;
    }
}
