<?php
namespace Services;

use Core\Database;
use Models\Grupo;
use Models\GrupoUsuario;
use Models\Pagamento;
use Models\Emprestimo;
use Services\LogService;
use Core\Sessao;
use Exception;

/**
 * Service responsável pelo fechamento financeiro do grupo
 */
class FechamentoService
{
    /**
     * Executa o fechamento completo da caixinha
     */
    public static function fecharGrupo(int $grupoId): void
    {
        $db = Database::conectar();
        $db->beginTransaction();

        try {
            $status = Grupo::obterStatus($grupoId);
            if ($status !== 'ATIVO') {
                throw new Exception('O grupo já está fechado.');
            }

            $totalPago   = Pagamento::totalPagoPorGrupo($grupoId);
            $emprestado  = Emprestimo::totalEmprestadoAtivo($grupoId);
            $taxasJuros  = Emprestimo::totalTaxasEJuros($grupoId);

            $saldoFinal = $totalPago - $emprestado + $taxasJuros;

            $totalCotas = GrupoUsuario::totalCotas($grupoId);
            if ($totalCotas <= 0) {
                throw new Exception('Grupo sem cotas válidas.');
            }

            $valorPorCota = $saldoFinal / $totalCotas;
            $usuarios = GrupoUsuario::listarCotasPorGrupo($grupoId);

            foreach ($usuarios as $usuario) {
                $valorRecebido = $valorPorCota * $usuario['quantidade_cotas'];

                $stmt = $db->prepare(
                    'INSERT INTO distribuicoes
                     (grupo_id, usuario_id, quantidade_cotas, valor_recebido)
                     VALUES (:grupo_id, :usuario_id, :cotas, :valor)'
                );

                $stmt->execute([
                    ':grupo_id'   => $grupoId,
                    ':usuario_id' => $usuario['usuario_id'],
                    ':cotas'      => $usuario['quantidade_cotas'],
                    ':valor'      => $valorRecebido
                ]);
            }

            Grupo::fechar($grupoId);

            LogService::registrar(
                Sessao::get('usuario_id'),
                'FECHAMENTO',
                "Grupo {$grupoId} fechado. Saldo final: {$saldoFinal}"
            );

            $db->commit();

        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
}