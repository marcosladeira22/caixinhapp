<?php
// Exibir erros apenas em desenvolvimento
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Autoload simples (similar ao Composer)
spl_autoload_register(function ($classe) {
    $caminho = __DIR__ . '/../app/' . str_replace('\\', '/', $classe) . '.php';
    if (file_exists($caminho)) {
        require $caminho;
    }
});

// Inicializa a aplicação
$app = new Core\App();
$app->executar();