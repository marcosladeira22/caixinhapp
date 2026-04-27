<?php
namespace Services;

use Models\Pagamento;
use Services\LogService;
use Core\Sessao;

/**
 * Regras de negócio relacionadas a pagamentos de cotas
 */
class PagamentoService
{
    /**
     * Verifica se o usuário está em dia com a cota no mês atual
     */
    public static function usuarioEstaEmDia(int $usuario_id, int $grupo_id): bool
    {
        $mesAtual = date('Y-m-01');

        $pagamentos = Pagamento::listarPorGrupoMes($grupo_id, $mesAtual);

        foreach ($pagamentos as $p) {
            if ($p['usuario_id'] == $usuario_id) {
                return !empty($p['pagamento_id']);
            }
        }

        return false;
    }

    /**
     * Regras de registro de pagamento de cotas
     */
    public static function registrarPagamento(
        int $usuario_id,
        int $grupo_id,
        int $quantidade_cotas,
        float $valor_cota
    ): void {
        $mesAtual = date('Y-m-01');

        // Valor total = cotas × valor da cota
        $valorTotal = $quantidade_cotas * $valor_cota;

        \Models\Pagamento::registrar([
            ':usuario_id'     => $usuario_id,
            ':grupo_id'       => $grupo_id,
            ':mes_referencia' => $mesAtual,
            ':valor'          => $valorTotal,
            ':data_pagamento' => date('Y-m-d'),
            ':dias_atraso'    => 0
        ]);

        // ✅ Registra log
        LogService::registrar(Sessao::get('usuario_id'),'PAGAMENTO',"Pagamento registrado para usuário {$usuario_id} no grupo {$grupo_id}");
    }
}