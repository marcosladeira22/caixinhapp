<?php

/**
 * Retorna a URL base do sistema
 */
function base_url(string $caminho = ''): string
{
    static $baseUrl = null;

    if ($baseUrl === null) {
        $config  = require __DIR__ . '/../../config/app.php';
        $baseUrl = rtrim($config['base_url'], '/');
    }

    if ($caminho) {
        return $baseUrl . '/' . ltrim($caminho, '/');
    }

    return $baseUrl;
}