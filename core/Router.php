<?php

// Classe responsável por controlar as rotas do sistema
class Router
{
    // Método principal que será chamado pelo index.php
    public function run()
    {
        // Captura a URL enviada pelo .htaccess
        // Se não existir, define um padrão
        $url = $_GET['url'] ?? 'home/index';

        // Quebra a URL em partes usando /
        // Ex: home/index → ['home', 'index']
        $url = explode('/', $url);

        // Define o nome do controller
        // ucfirst deixa a primeira letra maiúscula
        // home → HomeController
        $controllerName = ucfirst($url[0]) . 'Controller';

        // Define o método (ação)
        // Se não existir, usa "index"
        $method = $url[1] ?? 'index';

        // Caminho físico do controller
        $controllerFile = "../app/Controllers/$controllerName.php";

        // Verifica se o arquivo do controller existe
        if (!file_exists($controllerFile)) {
            die("Controller não encontrado.");
        }

        // Inclui o controller
        require_once $controllerFile;

        // Cria uma instância do controller
        $controller = new $controllerName();

        // Verifica se o método existe dentro do controller
        if (!method_exists($controller, $method)) {
            die("Método não encontrado.");
        }

        // Executa o método do controller
        $controller->$method();
    }
}
