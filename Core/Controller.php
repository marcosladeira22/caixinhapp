<?php

// Define o namespace da classe
namespace Core;

// Classe base para todos os controllers
class Controller
{
    // Armazena a URL base do projeto
    protected $base_url;

    public function __construct()
    {
        // Carrega as configurações do app
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

        // Inclui o header padrão
        require __DIR__ . "/../App/Views/layouts/header.php";

        // Inclui a view específica
        require __DIR__ . "/../App/Views/$view.php";

        // Inclui o footer padrão
        require __DIR__ . "/../App/Views/layouts/footer.php";
    }

    //método padrão de redirecionamento
    protected function redirect($path)
    {
        // Redireciona para a URL correta do projeto
        header("Location: {$this->base_url}{$path}");
        exit;
    }

    protected function auth()
    {
        // Verifica se o usuário está logado
        if (!isset($_SESSION['user'])) {
            // Se não estiver, redireciona para login
            $this->redirect('/login');
        }
    }
}
