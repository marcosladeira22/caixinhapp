<?php
// Inicia sessão GLOBAL do sistema
session_start();

// Captura URL amigável manual
$url = $_GET['url'] ?? '/';

// Inclui rotas
require_once __DIR__ . '/routes/web.php';

?>