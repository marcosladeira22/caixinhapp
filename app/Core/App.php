<?php
namespace Core;

class App
{
    public function executar()
    {
        Sessao::iniciar();

        $router = new Router();
        $router->resolver();
    }
}
