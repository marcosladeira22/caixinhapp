<?php
namespace Services;

use Models\Pagamento;
use Services\LogService;
use Core\Sessao;

/**
 * Service responsável pelas regras de negócio de pagamentos de cotas
 */
class PagamentoService
{
    /**
     * Verifica se o usuário está em dia com a cota
     */
    public static function usuarioEstaEmDia(
        int $usuarioId,
        int $grupoId
    ): bool {
        $mesReferencia = date('Y-m-01');

        return self::existePagamentoNoMes(
            $usuarioId,
            $grupoId,
            $mesReferencia
        );
    }

    /**
     * Registra o pagamento da cota
     */
    public static function registrarPagamento(
        int $usuarioId,
        int $grupoId,
        int $quantidadeCotas,
        float $valorCota
    ): void {
        $mesReferencia = date('Y-m-01');
        $valorTotal    = $quantidadeCotas * $valorCota;

        Pagamento::registrar(
            $usuarioId,
            $grupoId,
            $mesReferencia,
            $valorTotal,
            date('Y-m-d'),
            0
        );

        LogService::registrar(
            Sessao::get('usuario_id'),
            'PAGAMENTO',
            "Pagamento registrado para usuário {$usuarioId} no grupo {$grupoId}"
        );
    }

    /**
     * Verifica se existe pagamento registrado para o mês
     */
    private static function existePagamentoNoMes(
        int $usuarioId,
        int $grupoId,
        string $mesReferencia
    ): bool {
        $db = \Core\Database::conectar();

        $stmt = $db->prepare(
            'SELECT COUNT(*)
            FROM pagamentos
            WHERE usuario_id = :usuario_id
            AND grupo_id = :grupo_id
            AND mes_referencia = :mes'
        );

        $stmt->execute([
            ':usuario_id' => $usuarioId,
            ':grupo_id'   => $grupoId,
            ':mes'        => $mesReferencia
        ]);

        return (int)$stmt->fetchColumn() > 0;
    }
}