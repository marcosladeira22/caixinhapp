<?php
namespace Services;

use Models\Usuario;
use Models\GrupoUsuario;
use Core\Sessao;
use Exception;

/**
 * Service responsável pela gestão de usuários no grupo
 */
class UsuarioGrupoService
{
    /**
     * Cria ou reutiliza usuário e associa ao grupo
     */
    public static function adicionarAoGrupo(
        int $grupoId,
        string $nome,
        string $email,
        ?string $telefone,
        string $sexo,
        string $nivel,
        int $quantidadeCotas
    ): void {
        $usuario = Usuario::buscarPorEmail($email);

        if (!$usuario) {
            // senha temporária
            $senhaTemporaria = password_hash('123456', PASSWORD_DEFAULT);

            $usuarioId = Usuario::criar(
                $nome,
                $email,
                $senhaTemporaria,
                $telefone,
                $sexo
            );
        } else {
            $usuarioId = $usuario['id'];
        }

        GrupoUsuario::adicionar(
            $usuarioId,
            $grupoId,
            $nivel,
            $quantidadeCotas
        );

        LogService::registrar(
            Sessao::get('usuario_id'),
            'USUARIO_GRUPO',
            "Adicionou usuário {$usuarioId} ao grupo {$grupoId}"
        );
    }

    /**
     * Lista usuários do grupo com paginação
     */
    public static function listar(
        int $grupoId,
        int $pagina,
        int $porPagina
    ): array {
        $total = GrupoUsuario::contarPorGrupo($grupoId);

        $paginator = new \Core\Paginator($total, $pagina, $porPagina);

        $usuarios = GrupoUsuario::listarPorGrupoPaginado(
            $grupoId,
            $paginator->porPagina,
            $paginator->offset
        );

        return [
            'usuarios'  => $usuarios,
            'paginator' => $paginator
        ];
    }

    /**
     * Atualiza vínculo usuário-grupo
     */
    public static function atualizarVinculo(
        int $id,
        int $quantidadeCotas,
        string $nivel,
        bool $ativo
    ): void {
        $registro = GrupoUsuario::buscarPorId($id);

        if (!$registro) {
            throw new Exception('Registro não encontrado.');
        }

        // ❌ Admin não pode se remover
        if (
            $registro['usuario_id'] === Sessao::get('usuario_id') &&
            (!$ativo || $nivel !== 'ADMIN')
        ) {
            throw new Exception('Você não pode remover seu próprio acesso.');
        }

        GrupoUsuario::atualizar(
            $id,
            $quantidadeCotas,
            $nivel,
            $ativo
        );

        LogService::registrar(
            Sessao::get('usuario_id'),
            'USUARIO_GRUPO',
            "Atualizou usuário {$registro['usuario_id']} no grupo {$registro['grupo_id']}"
        );
    }
}