<?php

// Exibe erros (modo estudo)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Carrega o autoload
require_once "../autoload.php";

// Usa a classe Router
use Core\Router;

// Inicia o sistema
$router = new Router();

//Executa o Sistema de rotas
$router->run();
