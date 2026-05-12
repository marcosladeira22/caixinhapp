<?php
namespace Core;

/**
 * Classe responsável por iniciar a aplicação
 * (Front Controller)
 */
class App
{
    /**
     * Executa o ciclo da aplicação
     */
    public function executar(): void
    {

        // Inicia sessão global do sistema
        Sessao::iniciar();

        $config = require __DIR__ . '/../../config/app.php';

        // ✅ Controle de ambiente
        if ($config['env'] === 'dev') {
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
        } else {
            ini_set('display_errors', 0);
            ini_set('log_errors', 1);
        }

        // ✅ Headers de segurança
        header('X-Frame-Options: SAMEORIGIN');
        header('X-Content-Type-Options: nosniff');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: no-referrer-when-downgrade');
        header("Content-Security-Policy: default-src 'self' https: data:; script-src 'self' https://cdn.jsdelivr.net; style-src 'self' https://cdn.jsdelivr.net;");

        
        // ✅ Proteção CSRF global
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['_token'] ?? null;

            if (!\Core\Csrf::validarToken($token)) {
                http_response_code(403);
                die('Token CSRF inválido.');
            }
        }

        // Resolve rota e despacha controller
        $router = new Router();
        $router->resolver();
    }
}