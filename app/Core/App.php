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