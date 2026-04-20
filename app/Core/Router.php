<?php
namespace Core;

class Router
{
    public function resolver()
    {
        $rota = $_GET['rota'] ?? 'auth@login';

        [$controller, $metodo] = explode('@', $rota);

        $controller = ucfirst($controller) . 'Controller';
        $classe = "Controllers\\$controller";

        if (!class_exists($classe)) {
            die("Controller não encontrado");
        }

        $objeto = new $classe;

        if (!method_exists($objeto, $metodo)) {
            die("Método não encontrado");
        }

        $objeto->$metodo();
    }
}