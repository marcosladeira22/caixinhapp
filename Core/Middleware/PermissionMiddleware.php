<?php

namespace Core\Middleware;

class PermissionMiddleware
{
    public static function handle($controller, $method)
    {
        /*
        |--------------------------------------------------------------------------
        | MÉTODOS PÚBLICOS (NÃO EXIGEM PERMISSÃO)
        |--------------------------------------------------------------------------
        */
        $publicMethods = ['index', 'logout'];

        if (in_array($method, $publicMethods)) {
            return true;
        }

        /*
        |--------------------------------------------------------------------------
        | USUÁRIO NÃO LOGADO
        |--------------------------------------------------------------------------
        */
        if (!isset($_SESSION['user'])) {
            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | ADMIN TEM ACESSO TOTAL
        |--------------------------------------------------------------------------
        */
        if ($_SESSION['user']['role'] === 'admin') {
            return true;
        }

        /*
        |--------------------------------------------------------------------------
        | MAPA DE PERMISSÕES POR CONTROLLER / ACTION
        |--------------------------------------------------------------------------
        */
        $map = [
            'UserController' => [
                'index'   => 'view_user',
                'create'  => 'create_user',
                'store'   => 'create_user',
                'edit'    => 'edit_user',
                'update'  => 'edit_user',
                'delete'  => 'delete_user',
                'restore' => 'restore_user',
                'deleted' => 'view_deleted_users'
            ],
        ];

        // Nome curto do controller (UserController)
        $controllerName = (new \ReflectionClass($controller))->getShortName();

        /*
        |--------------------------------------------------------------------------
        | SE NÃO EXISTE REGRA → BLOQUEIA POR SEGURANÇA
        |--------------------------------------------------------------------------
        */
        if (!isset($map[$controllerName][$method])) {
            return false;
        }

        $permission = $map[$controllerName][$method];

        /*
        |--------------------------------------------------------------------------
        | VERIFICA PERMISSÃO
        |--------------------------------------------------------------------------
        */
        return $controller->can($permission);
    }

}
