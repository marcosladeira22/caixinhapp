<?php

// Mostrar erros (apenas em desenvolvimento)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inicia sessão (login usa isso)
session_start();

// Autoload REAL do projeto
require_once __DIR__ . '/../autoload.php';

// Inicia o Router
$router = new Core\Router();

// Executa o roteamento
$router->run();
