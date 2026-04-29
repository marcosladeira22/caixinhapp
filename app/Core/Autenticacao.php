<?php
namespace Core;

/**
 * Responsável apenas por verificar autenticação
 */
class Autenticacao
{
    /**
     * Verifica se o usuário está autenticado
     */
    public static function verificar(): bool
    {
        return Sessao::get('usuario_id') !== null;
    }

    /**
     * Garante autenticação ou redireciona
     */
    public static function exigirLogin(): void
    {
        if (!self::verificar()) {
            header('Location: ' . base_url('?rota=auth@login'));
            exit;
        }
    }
}