<?php
namespace Services;

use Models\Usuario;
use Services\LogService;
use Exception;

/**
 * Service responsável pelas regras do usuário
 */
class UsuarioService
{
    /**
     * Retorna perfil do usuário
     */
    public static function obterPerfil(int $usuarioId): array
    {
        $usuario = Usuario::buscarPorId($usuarioId);

        if (!$usuario) {
            throw new Exception('Usuário não encontrado.');
        }

        return $usuario;
    }

    /**
     * Atualiza dados do perfil
     */
    public static function atualizarPerfil(
        int $usuarioId,
        string $nome,
        ?string $telefone,
        string $sexo,
        ?string $senha
    ): void {
        Usuario::atualizarDadosBasicos(
            $usuarioId,
            $nome,
            $telefone,
            $sexo
        );

        if (!empty($senha)) {
            Usuario::atualizarSenha($usuarioId, $senha);
        }

        LogService::registrar(
            $usuarioId,
            'PERFIL',
            'Usuário atualizou seus dados de perfil'
        );
    }
}