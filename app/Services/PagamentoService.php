<?php
namespace Services;

use Models\Pagamento;

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
}