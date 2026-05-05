<?php
namespace Services;

use Models\Grupo;
use Models\GrupoUsuario;
use Core\Sessao;

/**
 * Service responsável pelo domínio de grupos
 */
class GrupoService
{
    /**
     * Cria um grupo e associa o criador como ADMIN
     */
    public static function criarGrupo(array $dados): int
    {
        $grupoId = Grupo::criar(
            $dados['nome'],
            (float) $dados['valor_cota'],
            (float) $dados['emprestimo_min'],
            (float) $dados['emprestimo_max'],
            $dados['taxa_tipo'],
            (float) $dados['taxa_valor'],
            $dados['juros_tipo'],
            (float) $dados['juros_valor'],
            (int) $dados['dias_tolerancia']
        );

        GrupoUsuario::adicionar(
            Sessao::get('usuario_id'),
            $grupoId,
            'ADMIN',
            1
        );

        return $grupoId;
    }

    /**
     * Lista grupos do usuário
     */
    public static function listarDoUsuario(int $usuarioId): array
    {
        return Grupo::listarPorUsuario($usuarioId);
    }
}