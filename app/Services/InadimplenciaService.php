<?php
namespace Services;

use Models\Emprestimo;
use Models\Grupo;
use Services\ScoreService;
use DateTime;

/**
 * Service responsável por processar inadimplência de empréstimos
 *
 * IMPORTANTE:
 * Este service NÃO deve ser executado automaticamente em toda requisição.
 * Ele deve ser chamado:
 * - via cron
 * - via endpoint administrativo
 * - ou em ponto explícito do domínio
 */
class InadimplenciaService
{
    /**
     * Processa todos os empréstimos vencidos e aprovados
     */
    public static function processar(): void
    {
        $emprestimos = Emprestimo::listarAprovadosVencidos();

        foreach ($emprestimos as $emprestimo) {
            self::processarEmprestimo($emprestimo);
        }
    }

    /**
     * Processa inadimplência de um empréstimo específico
     */
    private static function processarEmprestimo(array $emprestimo): void
    {
        $dataVencimento = new DateTime($emprestimo['data_vencimento']);
        $hoje = new DateTime();

        $diasAtraso = $dataVencimento->diff($hoje)->days;

        if ($diasAtraso <= 0) {
            return;
        }

        $grupo = Grupo::buscarPorId((int)$emprestimo['grupo_id']);
        if (!$grupo) {
            return;
        }

        $juros = self::calcularJuros(
            (float)$emprestimo['valor_total'],
            $grupo,
            $diasAtraso
        );

        // Atualiza status do empréstimo
        Emprestimo::marcarComoAtrasado(
            (int)$emprestimo['id'],
            $juros
        );

        // Penaliza score via serviço dedicado
        ScoreService::penalizar(
            (int)$emprestimo['usuario_id'],
            (int)$emprestimo['grupo_id'],
            $diasAtraso
        );
    }

    /**
     * Calcula juros por atraso
     */
    private static function calcularJuros(
        float $valorTotal,
        array $grupo,
        int $diasAtraso
    ): float {
        if ($grupo['juros_tipo'] === 'fixo') {
            return $grupo['juros_valor'] * $diasAtraso;
        }

        // Percentual ao dia
        return ($valorTotal * ($grupo['juros_valor'] / 100)) * $diasAtraso;
    }
}