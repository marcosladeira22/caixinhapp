<?php
namespace Services;

use Models\GrupoUsuario;

/**
 * Service responsável pela regra de cálculo e ajuste de score
 */
class ScoreService
{
    /**
     * Penaliza o score do usuário por inadimplência
     */
    public static function penalizar(
        int $usuarioId,
        int $grupoId,
        int $diasAtraso
    ): void {
        $scoreAtual = GrupoUsuario::obterScore($usuarioId, $grupoId);

        $novoScore = self::calcularPenalidade($scoreAtual, $diasAtraso);

        // Garante limites
        $novoScore = max(0, min(1000, $novoScore));

        GrupoUsuario::atualizarScore($usuarioId, $grupoId, $novoScore);
    }

    /**
     * Bonifica o score do usuário por bom comportamento (pagamento em dia)
     */
    public static function bonificar(
        int $usuarioId,
        int $grupoId
    ): void {
        $scoreAtual = GrupoUsuario::obterScore($usuarioId, $grupoId);

        $novoScore = min(1000, $scoreAtual + 5);

        GrupoUsuario::atualizarScore($usuarioId, $grupoId, $novoScore);
    }

    /**
     * Regra de cálculo da penalidade por atraso
     */
    private static function calcularPenalidade(
        int $scoreAtual,
        int $diasAtraso
    ): int {
        $penalidade = $diasAtraso * 2;

        // Penalidade adicional para atrasos longos
        if ($diasAtraso > 10) {
            $penalidade += 20;
        }

        return $scoreAtual - $penalidade;
    }
}