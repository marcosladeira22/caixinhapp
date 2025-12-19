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

        // Remove possíveis barras extras
        $url = trim($url, '/');
        
        // Quebra a URL em partes usando /
        // Ex: home/index → ['home', 'index']
        $url = explode('/', $url);
        
        /*
         * Se a URL começar com "public",
         * removemos essa parte automaticamente
         */
        if ($url[0] === 'public') {
            array_shift($url);
        }

        // Define o nome do controller
        // ucfirst deixa a primeira letra maiúscula
        // home → HomeController
        $controllerName = ucfirst($url[0]) . 'Controller';

        // Define o método (ação)
        // Se não existir, usa "index"
        $method = $url[1] ?? 'index';

        // Parâmetros extras da URL
        $params = array_slice($url, 2);

        // Monta o namespace completo do controller
        $controllerClass = "App\\Controllers\\$controllerName";

        // Verifica se a classe existe
        if (!class_exists($controllerClass)) {
            die("Controller não encontrado.");
        }

        // Cria o controller
        $controller = new $controllerClass();

        /*
        |--------------------------------------------------------------------------
        | MIDDLEWARE SIMPLES DE AUTENTICAÇÃO
        |--------------------------------------------------------------------------
        | Aqui decidimos se a rota precisa de login
        */

        // Lista de controllers que NÃO exigem autenticação
        $publicControllers = [
            'AuthController'
        ];

        // Se o controller atual NÃO for público
        if (!in_array($controllerName, $publicControllers)) {

            // Se o usuário NÃO estiver logado
            if (!isset($_SESSION['user'])) {

                // Redireciona para a tela de login
                header('Location: /caixinhapp/public/auth/index');
                exit;
            }
        }

        // Verifica se o método existe no controller
        if (!method_exists($controller, $method)) {
            die("Método não encontrado.");
        }

        // Executa o método do controller com os parâmetros
        call_user_func_array([$controller, $method], $params);

    }
}
