<?php
namespace Core;

class Csrf
{
    /**
     * Gera ou retorna o token da sessão
     */
    public static function gerarToken(): string
    {
        $token = Sessao::get('_csrf_token');

        if (!$token) {
            $token = bin2hex(random_bytes(32));
            Sessao::set('_csrf_token', $token);
        }

        return $token;
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