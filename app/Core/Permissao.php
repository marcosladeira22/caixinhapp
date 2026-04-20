<?php
namespace Core;

use Models\GrupoUsuario;

class Permissao
{
    // Verifica se usuário é ADMIN no grupo
    public static function admin($grupo_id)
    {
        $usuario_id = Sessao::get('usuario_id');

        $nivel = GrupoUsuario::nivelUsuarioNoGrupo($usuario_id, $grupo_id);

        if ($nivel !== 'ADMIN') {
            die('Acesso negado. Permissão insuficiente.');
        }
    }
}