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

    /**
     * Exibe a tela de gerenciamento de permissões
     */
    public function index()
    {
        // Lista de roles fixas do sistema
        $roles = ['admin', 'manager', 'user'];

        // Todas as permissões cadastradas
        $permissions = $this->permission->getAll();

        // Permissões por role
        $rolePermissions = [];

        foreach ($roles as $role) {
            $rolePermissions[$role] = $this->permission->getPermissionsByRole($role);
        }

        // Envia para a view
        $this->view('permissions/index', [
            'title'           => 'Gerenciamento de Permissões',
            'roles'           => $roles,
            'permissions'     => $permissions,
            'rolePermissions' => $rolePermissions
        ]);
    }

    /**
     * Atualiza as permissões dos roles
     */
    public function update()
    {
        // 1️ - Validação CSRF
        if (!$this->validateCsrf()) {
            $this->setFlash('error', 'Token inválido. Recarregue a página.');
            $this->redirect('/permission/index');
        }

        // 2️ - Se não vier nada do formulário
        if (!isset($_POST['permissions']) || !is_array($_POST['permissions'])) {
            $this->setFlash('error', 'Nenhuma permissão enviada.');
            $this->redirect('/permission/index');
        }

        // 3️ - Percorre cada ROLE enviada no formulário
        foreach ($_POST['permissions'] as $role => $permissions) {

            // Remove TODAS as permissões atuais desse role
            $this->permission->removeAllFromRole($role);

            // Se não tiver nenhuma marcada, pula
            if (!is_array($permissions)) {
                continue;
            }

            // 4️ - Associa novamente as permissões selecionadas
            foreach ($permissions as $permissionName) {
                $this->permission->assignPermissionToRole(
                    $role,
                    $permissionName
                );
            }
        }

        // 5️ - Log de auditoria
        $this->log('update_permissions','Administrador atualizou permissões dos papéis');

        // 6️ - Mensagem de sucesso
        $this->setFlash('success', 'Permissões atualizadas com sucesso.');

        // 7️ - Redireciona
        $this->redirect('/permission/index');
    }

}
