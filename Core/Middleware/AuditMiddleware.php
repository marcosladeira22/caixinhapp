<?php

namespace Core\Middleware;

use App\Models\AuditLog;

class AuditMiddleware
{
    public static function handle($controller, $method, $params = [])
    {
        // 🔒 Usuário precisa estar logado
        if (!isset($_SESSION['user'])) {
            return;
        }

        $user = $_SESSION['user'];

    
        $log = new AuditLog();

        $log->create([
            'user_id'    => $user['id'],
            'role'       => $user['role'],
            'action'     => strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET'),
            'controller' => (new \ReflectionClass($controller))->getShortName(),
            'method'     => $method,
            'params'     => json_encode($params),
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
        ]);
    }
}
