<?php

// Define o namespace da classe
namespace Core;

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

        // Monta o namespace completo do controller
        $controllerClass = "App\\Controllers\\$controllerName";

        // Verifica se a classe existe
        if (!class_exists($controllerClass)) {
            die("Controller não encontrado.");
        }

        // Cria o controller
        $controller = new $controllerClass();

        // Verifica se o método existe
        if (!method_exists($controller, $method)) {
            die("Método não encontrado.");
        }

        // Executa o método
        $controller->$method();
    }
}
