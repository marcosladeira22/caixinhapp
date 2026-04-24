<?php
namespace Core;
use Services\InadimplenciaService;

class App
{
    public function executar()
    {
        Sessao::iniciar();

        // ✅ Processa inadimplência sempre que o sistema é acessado
        InadimplenciaService::processar();

        $router = new Router();
        $router->resolver();
    }
}
