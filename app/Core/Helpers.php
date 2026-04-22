<?php

/**
 * Retorna a URL base do sistema
 */
function base_url(string $caminho = ''): string
{
    $config = require __DIR__ . '/../../config/app.php';

    $url = rtrim($config['base_url'], '/');

    if ($caminho) {
        $url .= '/' . ltrim($caminho, '/');
    }

    return $url;
}