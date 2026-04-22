<?php
namespace Services;

use Core\Database;

class ScoreService
{
    /**
     * Atualiza o score de um usuário em um grupo
     * com base nos dias de atraso do pagamento
     */
    public static function atualizarScore(
        int $usuario_id,
        int $grupo_id,
        int $dias_atraso
    ): void
    {
        $db = Database::conectar();

        // Busca score atual
        $sqlScore = "SELECT score 
                     FROM grupos_usuarios
                     WHERE usuario_id = :usuario_id
                     AND grupo_id = :grupo_id";

        $stmt = $db->prepare($sqlScore);
        $stmt->execute([
            ':usuario_id' => $usuario_id,
            ':grupo_id'   => $grupo_id
        ]);

        $scoreAtual = (int) $stmt->fetchColumn();

        // Calcula novo score
        $novoScore = self::calcularNovoScore($scoreAtual, $dias_atraso);

        // Garante limites (não deixar negativo ou absurdo)
        $novoScore = max(0, min(1000, $novoScore));

        // Atualiza no banco
        $sqlAtualizar = "UPDATE grupos_usuarios
                         SET score = :score
                         WHERE usuario_id = :usuario_id
                         AND grupo_id = :grupo_id";

        $stmt = $db->prepare($sqlAtualizar);
        $stmt->execute([
            ':score'      => $novoScore,
            ':usuario_id' => $usuario_id,
            ':grupo_id'   => $grupo_id
        ]);
    }

    /**
     * Regra de cálculo do score
     */
    private static function calcularNovoScore(
        int $scoreAtual,
        int $dias_atraso
    ): int
    {
        // ✅ Pagamento em dia
        if ($dias_atraso === 0) {
            return $scoreAtual + 5;
        }

        // ✅ Pagamento com atraso
        $penalidade = $dias_atraso * 2;
        $novoScore = $scoreAtual - $penalidade;

        // Penalidade extra para atrasos longos
        if ($dias_atraso > 10) {
            $novoScore -= 20;
        }

        return $novoScore;
    }
}