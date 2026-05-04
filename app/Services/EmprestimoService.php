<?php
namespace Services;

use Models\Grupo;
use Models\GrupoUsuario;
use Models\Emprestimo;
use Exception;

/**
 * Service responsável pelas regras de negócio de empréstimos
 */
class EmprestimoService
{
    /**
     * Valida se o usuário pode solicitar um empréstimo
     */
    public static function validarSolicitacao(
        int $usuarioId,
        int $grupoId,
        float $valor
    ): void {
        // Verifica vínculo com o grupo
        $grupoUsuario = GrupoUsuario::buscarPorUsuarioEGrupo($usuarioId, $grupoId);

        if (!$grupoUsuario) {
            throw new Exception('Usuário não pertence ao grupo.');
        }

        // ❌ Bloqueio por inadimplência de cota
        if (!PagamentoService::usuarioEstaEmDia($usuarioId, $grupoId)) {
            throw new Exception('Você precisa estar em dia com a cota para solicitar empréstimo.');
        }

        // ❌ Já possui empréstimo em aberto
        if (Emprestimo::existeEmprestimoAberto($usuarioId, $grupoId)) {
            throw new Exception('Usuário já possui empréstimo em aberto.');
        }

        // ❌ Score mínimo
        if ($grupoUsuario['score'] < 50) {
            throw new Exception('Score insuficiente para empréstimo.');
        }

        // Busca grupo
        $grupo = Grupo::buscarPorId($grupoId);
        if (!$grupo) {
            throw new Exception('Grupo não encontrado.');
        }

        // ✅ Valor mínimo do grupo
        if ($valor < $grupo['emprestimo_min']) {
            throw new Exception(
                'O valor mínimo para empréstimo neste grupo é R$ ' .
                number_format($grupo['emprestimo_min'], 2, ',', '.')
            );
        }

        // ✅ Limite máximo baseado no score
        $limitePermitido = self::limitePorScore($grupo, (int) $grupoUsuario['score']);

        if ($valor > $limitePermitido) {
            throw new Exception(
                'Com seu score atual, o limite máximo permitido é R$ ' .
                number_format($limitePermitido, 2, ',', '.')
            );
        }
    }

    /**
     * Cria a solicitação de empréstimo
     */
    public static function solicitar(
        int $usuarioId,
        int $grupoId,
        float $valor
    ): int {
        self::validarSolicitacao($usuarioId, $grupoId, $valor);

        $grupo = Grupo::buscarPorId($grupoId);
        if (!$grupo) {
            throw new Exception('Grupo não encontrado.');
        }

        $taxa = self::calcularTaxa($valor, $grupo);
        $valorTotal = $valor + $taxa;

        return Emprestimo::criar(
            $usuarioId,
            $grupoId,
            $valor,
            $taxa,
            $valorTotal
        );
    }

    /**
     * Calcula taxa aplicada (fixa ou percentual)
     */
    private static function calcularTaxa(float $valor, array $grupo): float
    {
        if ($grupo['taxa_tipo'] === 'fixo') {
            return (float) $grupo['taxa_valor'];
        }

        return ($valor * $grupo['taxa_valor']) / 100;
    }

    /**
     * Calcula limite permitido com base no score
     */
    private static function limitePorScore(array $grupo, int $score): float
    {
        if ($score < 80) {
            return $grupo['emprestimo_max'] * 0.3;
        }

        return (float) $grupo['emprestimo_max'];
    }

    
}