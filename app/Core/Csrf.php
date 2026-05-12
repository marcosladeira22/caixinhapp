<?php
namespace Core;

class Csrf
{
    /**
     * Gera ou retorna o token da sessão
     */
    public static function gerarToken(): string
    {
        if (!Sessao::has('_csrf_token')) {
            Sessao::set('_csrf_token', bin2hex(random_bytes(32)));
        }

        return Sessao::get('_csrf_token');
    }

    /**
     * Valida o token recebido
     */
    public static function validarToken(?string $token): bool
    {
        $tokenSessao = Sessao::get('_csrf_token');

        if (!$token || !$tokenSessao) {
            return false;
        }

        return hash_equals($tokenSessao, $token);
    }
}