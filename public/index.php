<?php
// Define que erros devem ser exibidos (bom para estudo)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Importa o arquivo responsável pelas rotas
// Ele decide qual controller será chamado
require_once "../core/Router.php";

// Cria um novo objeto Router
$router = new Router();

// Executa o sistema de rotas
$router->run();
