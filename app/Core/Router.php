<?php
namespace Core;

/**
 * Router responsável por despachar a requisição
 * para o Controller e método corretos
 */
class Router
{
    /**
     * Resolve a rota atual
     */
    public function resolver(): void
    {
        // Rota padrão
        $rota = $_GET['rota'] ?? 'auth@login';

        // Validação básica do formato controller@metodo
        if (!str_contains($rota, '@')) {
            $this->erro('Rota inválida');
            return;
        }

        [$controller, $metodo] = explode('@', $rota, 2);

        // Normaliza controller
        $controller = ucfirst($controller) . 'Controller';
        $classe = "Controllers\\{$controller}";

        // Verifica se a classe existe
        if (!class_exists($classe)) {
            $this->erro('Controller não encontrado');
            return;
        }

        $objeto = new $classe;

        // Verifica se o método existe e é público
        if (
            !method_exists($objeto, $metodo) ||
            !is_callable([$objeto, $metodo])
        ) {
            $this->erro('Método não encontrado');
            return;
        }

        // Executa a action
        $objeto->$metodo();
    }

    /**
     * Tratamento simples de erro do Router
     * Futuramente pode virar página 404/500
     */
    private function erro(string $mensagem): void
    {
        http_response_code(404);
        echo $mensagem;
    }
}