<?php
namespace Core;

/**
 * Controller base do sistema
 * Responsável por renderizar views e layouts
 */
abstract class Controller
{
    /**
     * Renderiza uma view dentro de um layout
     */
    protected function view(
        string $view,
        array $dados = [],
        string $layout = 'principal'
    ): void {
        $viewPath = __DIR__ . '/../Views/' . $view . '.php';
        $layoutPath = __DIR__ . '/../Views/layouts/' . $layout . '.php';

        // Valida existência da view
        if (!file_exists($viewPath)) {
            $this->renderErro("View '{$view}' não encontrada.");
            return;
        }

        // Valida existência do layout
        if (!file_exists($layoutPath)) {
            $this->renderErro("Layout '{$layout}' não encontrado.");
            return;
        }

        // Isola dados da view (sem extract indiscriminado)
        $dadosView = $dados;

        // Captura conteúdo da view
        ob_start();
        require $viewPath;
        $conteudo = ob_get_clean();

        // Renderiza layout
        require $layoutPath;
    }

    /**
     * Redirecionamento padrão
     */
    protected function redirect(string $rota): void
    {
        header('Location: ' . base_url($rota));
        exit;
    }

    /**
     * Renderiza erro simples
     * (pode evoluir para página 404/500)
     */
    protected function renderErro(string $mensagem, int $statusCode = 500): void
    {
        http_response_code($statusCode);
        echo $mensagem;
    }
}