<?php
namespace Core;

use Models\GrupoUsuario;

/**
 * Responsável por autorização (permissões)
 */
class Permissao
{
    /**
     * Verifica se usuário é ADMIN no grupo
     */
    public static function ehAdmin(int $grupo_id): bool
    {
        $usuario_id = Sessao::get('usuario_id');

        if (!$usuario_id) {
            return false;
        }

        $nivel = GrupoUsuario::nivelUsuarioNoGrupo($usuario_id, $grupo_id);

        return $nivel === 'ADMIN';
    }

    /**
     * Exige permissão de ADMIN no grupo
     */
    public static function exigirAdmin(int $grupo_id): void
    {
        if (!self::ehAdmin($grupo_id)) {
            http_response_code(403);
            echo 'Acesso negado. Permissão insuficiente.';
            exit;
        }
    }
}
