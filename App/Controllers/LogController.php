<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Log;

class LogController extends Controller
{
    private $logModel;

    public function __construct()
    {
        parent::__construct();

        // Apenas admin
        if (!$this->hasRole(['admin'])) {
            $this->setFlash('error', 'Acesso negado.');
            $this->redirect('/user/index');
        }

        $this->logModel = new Log();
    }

    public function index()
    {
        // Página atual
        $page = $_GET['page'] ?? 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        // Filtros
        $search = $_GET['search'] ?? '';
        $action = $_GET['action'] ?? '';

        // Busca logs
        $logs = $this->logModel->searchPaginated(
            $search,
            $action,
            $limit,
            $offset
        );

        // Total de registros
        $total = $this->logModel->countFiltered($search, $action);
        $pages = ceil($total / $limit);

        // Envia para view
        $this->view('logs/index', [
            'title'  => 'Logs do Sistema',
            'logs'   => $logs,
            'pages'  => $pages,
            'page'   => $page,
            'search' => $search,
            'action' => $action
        ]);
    }
}
