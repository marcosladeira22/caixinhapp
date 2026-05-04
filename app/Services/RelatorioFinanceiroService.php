<?php
namespace Services;

use Models\Pagamento;
use Models\Emprestimo;

/**
 * Service responsável por gerar relatórios financeiros
 * NÃO altera estado do sistema
 */
class RelatorioFinanceiroService
{
    /**
     * Gera relatório financeiro consolidado de um grupo
     */
    public static function gerar(int $grupoId): array
    {
        $totalPagamentos = Pagamento::totalPagoPorGrupo($grupoId);
        $totalEmprestado = Emprestimo::totalEmprestadoAtivo($grupoId);
        $totalTaxas      = Emprestimo::totalTaxasEJuros($grupoId);

        $saldoAtual = $totalPagamentos - $totalEmprestado + $totalTaxas;

        return [
            'total_pagamentos' => $totalPagamentos,
            'total_emprestado' => $totalEmprestado,
            'total_taxas'      => $totalTaxas,
            'saldo_atual'      => $saldoAtual,
        ];
    }
}