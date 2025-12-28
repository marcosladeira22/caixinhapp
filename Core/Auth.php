<?php
namespace Core;

use App\Models\Permission;

class Auth
{
    public static function refreshPermissions(): void
    {
        if (!isset($_SESSION['user']['id'])) {
            return;
        }

        $_SESSION['permissions'] = Permission::loadByUser(
            $_SESSION['user']['id']
        );
    }
}
