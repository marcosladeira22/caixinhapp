<?php

// Função que será chamada automaticamente
// sempre que uma classe for utilizada
spl_autoload_register(function ($class) {

    // Substitui as barras invertidas do namespace
    // por barras normais de diretório
    $class = str_replace('\\', '/', $class);

    // Caminhos base das classes
    $paths = [
        __DIR__ . '/',
    ];

    // Percorre todos os caminhos possíveis
    foreach ($paths as $path) {

        // Monta o caminho completo do arquivo
        $file = $path . $class . '.php';

        // Verifica se o arquivo existe
        if (file_exists($file)) {

            // Se existir, carrega o arquivo
            require_once $file;
            
            // Para a execução da função
            return;
        }
    }
});