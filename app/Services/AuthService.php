<?php
namespace Services;

use Models\Usuario;
use Core\Sessao;
use Exception;

/**
 * Service responsável por autenticação e cadastro
 */
class AuthService
{
    /**
     * Realiza login do usuário
     */
    public static function login(string $email, string $senha): void
    {
        $usuario = Usuario::buscarPorEmail($email);

        if (!$usuario || !password_verify($senha, $usuario['senha'])) {
            throw new Exception('E-mail ou senha incorretos.');
        }

        Sessao::set('usuario_id', $usuario['id']);
        Sessao::set('usuario_nome', $usuario['nome']);
    }

    /**
     * Realiza cadastro de usuário
     */
    public static function cadastrar(
        string $nome,
        string $email,
        string $senha,
        ?string $telefone,
        string $sexo
    ): int {
        if (Usuario::buscarPorEmail($email)) {
            throw new Exception('E-mail já cadastrado.');
        }

        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        return Usuario::criar(
            $nome,
            $email,
            $senhaHash,
            $telefone,
            $sexo
        );
    }
}