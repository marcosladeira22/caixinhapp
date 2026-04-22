<?php
namespace Core;

class Controller
{
    /**
     * Renderiza uma view dentro de um layout
     */
    protected function view(string $view, array $dados = [], string $layout = 'principal')
    {
        // Extrai variáveis para a view
        extract($dados);

        // Captura o conteúdo da view
        ob_start();
        require __DIR__ . '/../Views/' . $view . '.php';
        $conteudo = ob_get_clean();

        // Carrega o layout
        require __DIR__ . '/../Views/layouts/' . $layout . '.php';
    }
}