<?php
namespace Services;

use Models\Grupo;
use Models\GrupoUsuario;
use Models\Emprestimo;
use Exception;

class EmprestimoService
{
    /**
     * Valida se o usuário pode solicitar empréstimo
     */
    public static function validarSolicitacao(
        int $usuario_id,
        int $grupo_id,
        float $valor
    ): void
    {
        $dadosGrupoUsuario = GrupoUsuario::buscar($usuario_id, $grupo_id);

        if (!$dadosGrupoUsuario) {
            throw new Exception('Usuário não pertence ao grupo.');
        }

        // ❌ Empréstimo em aberto
        if (Emprestimo::possuiEmprestimoAberto($usuario_id, $grupo_id)) {
            throw new Exception('Usuário já possui empréstimo em aberto.');
        }

        // ❌ Score mínimo
        if ($dadosGrupoUsuario['score'] < 50) {
            throw new Exception('Score insuficiente para empréstimo.');
        }

        // ✅ Limites do grupo
        $grupo = Grupo::buscarPorId($grupo_id);

        if (!$grupo) {
            throw new Exception('Grupo não encontrado.');
        }


        if ($valor < $grupo['emprestimo_min'] || $valor > $grupo['emprestimo_max']) {
            throw new Exception('Valor fora dos limites permitidos.');
        }
    }

    /**
     * Calcula taxa aplicada (fixa ou percentual)
     */
    public static function calcularTaxa(float $valor, array $grupo): float
    {
        if ($grupo['taxa_tipo'] === 'fixo') {
            return $grupo['taxa_valor'];
        }

        return ($valor * $grupo['taxa_valor']) / 100;
    }

    /**
     * Cria a solicitação de empréstimo
     */
    public static function solicitar(
        int $usuario_id,
        int $grupo_id,
        float $valor
    ): void
    {
        self::validarSolicitacao($usuario_id, $grupo_id, $valor);

        
        $grupo = Grupo::buscarPorId($grupo_id);

        if (!$grupo) {
            throw new Exception('Grupo não encontrado.');
        }

        $taxa = self::calcularTaxa($valor, $grupo);

        Emprestimo::criar([
            ':usuario_id'       => $usuario_id,
            ':grupo_id'         => $grupo_id,
            ':valor_solicitado' => $valor,
            ':taxa_aplicada'    => $taxa,
            ':valor_total'      => ($valor + $taxa)
        ]);
    }
}