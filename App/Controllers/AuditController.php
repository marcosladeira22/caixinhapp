<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\AuditLog;

class AuditController extends Controller
{
    public function index()
    {
        // Permissão
        if (!$this->can('view_audit')) {
            $this->setFlash('error', 'Acesso negado.');
            $this->redirect('/user/index');
        }

        // Paginação
        $page  = $_GET['page'] ?? 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        // Filtros
        $userId = $_GET['user_id'] ?? null;
        $action = $_GET['action'] ?? null;

        $model = new \App\Models\AuditLog();

        $logs = $model->searchPaginated($userId, $action, $limit, $offset);

        $total = $model->countFiltered($userId, $action);
        $pages = ceil($total / $limit);

        $this->view('audit/index', [
            'title'  => 'Logs de Auditoria',
            'logs'   => $logs,
            'page'   => $page,
            'pages'  => $pages,
            'userId' => $userId,
            'action' => $action
        ]);
    }

}
