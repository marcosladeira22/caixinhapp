<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Permission;

class PermissionController extends Controller
{
    private $permission;

    public function __construct()
    {
        parent::__construct();

        // Apenas ADMIN acessa
        if (!$this->hasRole(['admin'])) {
            $this->setFlash('error', 'Acesso negado.');
            $this->redirect('/home/index');
        }

        $this->permission = new Permission();
    }

    // Tela principal
    public function index()
    {
        // Roles do sistema (fixo por enquanto)
        $roles = ['admin', 'manager', 'user'];

        // Todas as permissões
        $permissions = $this->permission->all();

        // Permissões por role
        $rolePermissions = [];

        foreach ($roles as $role) {
            $rolePermissions[$role] = $this->permission->getByRole($role);
        }

        $this->view('permissions/index', [
            'title' => 'Gerenciamento de Permissões',
            'roles' => $roles,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions
        ]);
    }

    // Atualiza permissões
    public function update()
    {
        if (!$this->validateCsrf()) {
            die('Token CSRF inválido');
        }

        $role = $_POST['role'];
        $permissions = $_POST['permissions'] ?? [];

        // Remove todas do role
        foreach ($this->permission->getByRole($role) as $permId) {
            $this->permission->detach($role, $permId);
        }

        // Adiciona as marcadas
        foreach ($permissions as $permId) {
            $this->permission->attach($role, $permId);
        }

        $this->log('UPDATE_ROLE_PERMISSIONS',"Permissões atualizadas para o role {$role}");

        $this->setFlash('success', 'Permissões atualizadas com sucesso.');
        $this->redirect('/permission/index');
    }
}
