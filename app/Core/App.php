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

        // Resolve rota e despacha controller
        $router = new Router();
        $router->resolver();
    }
}