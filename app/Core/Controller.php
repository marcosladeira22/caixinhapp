<?php
namespace Core;

class Controller
{
    protected function view($arquivo, $dados = [])
    {
        extract($dados);
        require __DIR__ . '/../Views/' . $arquivo . '.php';
    }
}