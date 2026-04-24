<?php
namespace Services;

use Core\Database;
use Exception;

class FechamentoService
{
    /**
     * Executa o fechamento completo da caixinha
     */
    public static function fecharGrupo(int $grupo_id): void
    {
        $db = Database::conectar();
        $db->beginTransaction();

        try {
            // 🔒 Verifica se grupo está ativo
            $grupo = $db->prepare("SELECT status FROM grupos WHERE id = :id");

            $grupo->execute([':id' => $grupo_id]);
            $status = $grupo->fetchColumn();

            if ($status !== 'ATIVO') {
                throw new Exception('O grupo já está fechado.');
            }

            // ✅ Saldo final
            $saldoFinal = self::calcularSaldoFinal($grupo_id);

            // ✅ Total de cotas do grupo
            $totalCotas = self::totalCotasGrupo($grupo_id);

            if ($totalCotas <= 0) {
                throw new Exception('Grupo sem cotas válidas.');
            }

            // ✅ Valor de cada cota
            $valorPorCota = $saldoFinal / $totalCotas;

            // ✅ Distribuição para cada usuário
            $usuarios = self::usuariosGrupo($grupo_id);

            foreach ($usuarios as $u) {
                $valorRecebido = $valorPorCota * $u['quantidade_cotas'];

                $stmt = $db->prepare("INSERT INTO distribuicoes
                                    (grupo_id, usuario_id, quantidade_cotas, valor_recebido)
                                    VALUES
                                    (:grupo_id, :usuario_id, :cotas, :valor)
                                    ");

                $stmt->execute([
                    ':grupo_id'   => $grupo_id,
                    ':usuario_id' => $u['usuario_id'],
                    ':cotas'      => $u['quantidade_cotas'],
                    ':valor'      => $valorRecebido
                ]);
            }

            // ✅ Fecha o grupo
            $db->prepare("UPDATE grupos
                        SET status = 'FECHADO',
                        fechado_em = NOW()
                        WHERE id = :id
                        ")->execute([':id' => $grupo_id]);

            $db->commit();

        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    /**
     * Calcula o saldo final da caixinha
     */
    private static function calcularSaldoFinal(int $grupo_id): float
    {
        $db = Database::conectar();

        $pagamentos = $db->prepare("SELECT SUM(valor) FROM pagamentos WHERE grupo_id = :id");
        $pagamentos->execute([':id' => $grupo_id]);

        $emprestimos = $db->prepare("SELECT SUM(valor_solicitado) FROM emprestimos 
                                    WHERE grupo_id = :id 
                                    AND status IN ('APROVADO','ATRASADO')"
                                    );
        $emprestimos->execute([':id' => $grupo_id]);

        $taxas = $db->prepare("SELECT SUM(taxa_aplicada + juros_aplicados)
                            FROM emprestimos 
                            WHERE grupo_id = :id"
                            );
        $taxas->execute([':id' => $grupo_id]);

        return
            (float)$pagamentos->fetchColumn()
            - (float)$emprestimos->fetchColumn()
            + (float)$taxas->fetchColumn();
    }

    /**
     * Total de cotas do grupo
     */
    private static function totalCotasGrupo(int $grupo_id): int
    {
        $db = Database::conectar();

        $stmt = $db->prepare("SELECT SUM(quantidade_cotas)
                            FROM grupos_usuarios
                            WHERE grupo_id = :id"
                            );
        $stmt->execute([':id' => $grupo_id]);

        return (int)$stmt->fetchColumn();
    }

    /**
     * Usuários do grupo
     */
    private static function usuariosGrupo(int $grupo_id): array
    {
        $db = Database::conectar();

        $stmt = $db->prepare("SELECT usuario_id, quantidade_cotas
                            FROM grupos_usuarios
                            WHERE grupo_id = :id"
                            );
        $stmt->execute([':id' => $grupo_id]);

        return $stmt->fetchAll();
    }
}