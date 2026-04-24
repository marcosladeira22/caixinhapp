<?php
namespace Services;

use Core\Database;

class RelatorioFinanceiroService
{
    /**
     * Gera relatório financeiro consolidado de um grupo
     */
    public static function gerar(int $grupo_id): array
    {
        $db = Database::conectar();

        // ✅ Total arrecadado (pagamentos)
        $totalPagamentos = $db->prepare("SELECT SUM(valor) 
                                        FROM pagamentos 
                                        WHERE grupo_id = :grupo_id"
                                        );

        $totalPagamentos->execute([':grupo_id' => $grupo_id]);

        // ✅ Total emprestado
        $totalEmprestado = $db->prepare("SELECT SUM(valor_solicitado) 
                                        FROM emprestimos 
                                        WHERE grupo_id = :grupo_id 
                                        AND status IN ('APROVADO','PAGO','ATRASADO')
                                        ");

        $totalEmprestado->execute([':grupo_id' => $grupo_id]);

        // ✅ Total de taxas e juros
        $totalTaxas = $db->prepare("SELECT SUM(taxa_aplicada + juros_aplicados)
                                    FROM emprestimos
                                    WHERE grupo_id = :grupo_id
                                    ");
                                    
        $totalTaxas->execute([':grupo_id' => $grupo_id]);

        return [
            'total_pagamentos' => (float) $totalPagamentos->fetchColumn(),
            'total_emprestado' => (float) $totalEmprestado->fetchColumn(),
            'total_taxas'      => (float) $totalTaxas->fetchColumn(),
            'saldo_atual'      => 
                (float)$totalPagamentos->fetchColumn() 
                - (float)$totalEmprestado->fetchColumn()
                + (float)$totalTaxas->fetchColumn(),
        ];
    }
}