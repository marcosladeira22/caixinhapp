<?php

require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/GrupoController.php';

// Captura URL amigável
$uri = '/' . ($_GET['url'] ?? '');

// Remove barra no final
$uri = rtrim($uri, '/');

// Se vazio vira "/"
if ($uri === '') {
    $uri = '/';
}

if (preg_match('#^/grupos/([0-9]+)$#', $uri, $matches)) {

    require_once __DIR__ . '/../app/middleware/AuthMiddleware.php';
    AuthMiddleware::verificar();

    require_once __DIR__ . '/../app/controllers/GrupoController.php';

    (new GrupoController())->show($matches[1]);
    return;
}

//Roteamento simples
switch ($uri) {

    // LOGIN
    case '/':
        (new AuthController())->login();
        break;

    case '/login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new AuthController())->autenticar(); // envia login
        } else {
            (new AuthController())->login(); // mostra tela
        }
        break;

    case '/logout':
        (new AuthController())->logout();
        break;

    // CADASTRO
    case '/register':
        (new AuthController())->register();
        break;

    case '/registrar':
        (new AuthController())->salvar();
        break;

    case '/grupos/create':
        require_once __DIR__ . '/../app/middleware/AuthMiddleware.php';
        AuthMiddleware::verificar();

        (new GrupoController())->create();
        break;

    case '/grupos/store':
        require_once __DIR__ . '/../app/middleware/AuthMiddleware.php';
        AuthMiddleware::verificar();

        (new GrupoController())->store();
        break;

    case '/grupos/adicionar-membro':
        require_once __DIR__ . '/../app/middleware/AuthMiddleware.php';
        AuthMiddleware::verificar();

        (new GrupoController())->adicionarMembro();
        break;
    
    case '/grupos/cotas/salvar':
        require_once __DIR__ . '/../app/middleware/AuthMiddleware.php';
        AuthMiddleware::verificar();

        (new GrupoController())->salvarCotas();
        break;

    case '/grupos/pagamentos/salvar':
        require_once __DIR__ . '/../app/middleware/AuthMiddleware.php';
        AuthMiddleware::verificar();

        (new GrupoController())->salvarPagamentos();
        break;
    
    //ROTA PROTEGIDA
    case '/dashboard':
        require_once __DIR__ . '/../app/middleware/AuthMiddleware.php';
        AuthMiddleware::verificar();
        require_once __DIR__ . '/../app/controllers/GrupoController.php';
        
        (new GrupoController())->index();
        break;

    case '/convite':
    (new AuthController())->convite();
    break;

    case '/convite/aceitar':
        (new AuthController())->aceitarConvite();
        break;
        
    default:
        echo "Página não encontrada";
        break;
}

?>