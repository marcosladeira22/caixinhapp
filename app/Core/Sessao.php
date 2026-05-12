<?php
namespace Core;

/**
 * Gerenciador de sessão do sistema
 */
class Sessao
{
    /**
     * Inicia a sessão se ainda não existir
     */
    public static function iniciar(): void
    {
        if (session_status() === PHP_SESSION_NONE) {

            
            // ✅ Configurações seguras antes de iniciar
            session_set_cookie_params([
                'lifetime' => 0,
                'path'     => '/',
                'secure'   => isset($_SERVER['HTTPS']),
                'httponly' => true,
                'samesite' => 'Strict'
            ]);

            session_start();
        }
    }

    /**
     * Define um valor na sessão
     */
    public static function set(string $chave, mixed $valor): void
    {
        $_SESSION[$chave] = $valor;
    }

    /**
     * Obtém um valor da sessão
     */
    public static function get(string $chave): mixed
    {
        return $_SESSION[$chave] ?? null;
    }

    /**
     * Verifica se uma chave existe na sessão
     */
    public static function has(string $chave): bool
    {
        return isset($_SESSION[$chave]);
    }

    /**
     * Remove uma chave específica da sessão
     */
    public static function forget(string $chave): void
    {
        unset($_SESSION[$chave]);
    }

    /**
     * Destroi completamente a sessão (logout seguro)
     */
    public static function destruir(): void
    {
        if (session_status() !== PHP_SESSION_NONE) {
            $_SESSION = [];

            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(
                    session_name(),
                    '',
                    time() - 42000,
                    $params['path'],
                    $params['domain'],
                    $params['secure'],
                    $params['httponly']
                );
            }
            session_regenerate_id(true);
            session_destroy();
        }
    }
}
