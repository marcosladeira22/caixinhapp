<?php
namespace Services;

use Models\Grupo;
use Models\GrupoUsuario;
use Exception;

/**
 * Service responsável pela lógica do Dashboard
 */
class DashboardService
{
    /**
     * Retorna os grupos do usuário
     */
    public static function gruposDoUsuario(int $usuarioId): array
    {
        return Grupo::listarPorUsuario($usuarioId);
    }

    /**
     * Monta os dados do dashboard do grupo
     */
    public static function dadosDoGrupo(
        int $usuarioId,
        int $grupoId
    ): array {
        $vinculo = GrupoUsuario::buscarPorUsuarioEGrupo($usuarioId, $grupoId);

        if (!$vinculo) {
            throw new Exception('Usuário não pertence a este grupo.');
        }

        if ($vinculo['nivel'] === 'ADMIN') {
            return [
                'tipo'     => 'ADMIN',
                'grupo_id' => $grupoId
                // futuramente: totais, inadimplentes etc
            ];
        }

        return [
            'tipo'             => 'MEMBRO',
            'grupo_id'         => $grupoId,
            'score'            => $vinculo['score'],
            'quantidade_cotas' => $vinculo['quantidade_cotas']
        ];
    }
}