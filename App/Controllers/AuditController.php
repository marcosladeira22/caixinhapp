<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\AuditLog;

class AuditController extends Controller
{
    public function index()
    {
        // 🔐 Verificação correta no SEU sistema
        if (!$this->can('view_audit')) {
            $_SESSION['flash']['error'] = 'Acesso negado.';
            $this->redirect('/user/index');
        }

        $logs = (new AuditLog())->all();

        $this->view('audit/index', [
            'title' => 'Logs de Auditoria',
            'logs'  => $logs
        ]);
    }
}
