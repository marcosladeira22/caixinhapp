<?php
namespace Models;

use Core\Database;

/**
 * Model Emprestimo
 * Responsável apenas por persistência e consultas
 */
class Emprestimo
{
    /**
     * Cria uma solicitação de empréstimo
     */
    public static function criar(
        int $usuarioId,
        int $grupoId,
        float $valorSolicitado,
        float $taxaAplicada,
        float $valorTotal
    ): int {
        $db = Database::conectar();

        $stmt = $db->prepare(
            'INSERT INTO emprestimos
             (usuario_id, grupo_id, valor_solicitado, taxa_aplicada, valor_total)
             VALUES
             (:usuario_id, :grupo_id, :valor_solicitado, :taxa_aplicada, :valor_total)'
        );

        $stmt->execute([
            ':usuario_id'       => $usuarioId,
            ':grupo_id'         => $grupoId,
            ':valor_solicitado' => $valorSolicitado,
            ':taxa_aplicada'    => $taxaAplicada,
            ':valor_total'      => $valorTotal
        ]);

        return (int) $db->lastInsertId();
    }

    /**
     * Verifica se existe empréstimo em aberto (consulta)
     */
    public static function existeEmprestimoAberto(
        int $usuarioId,
        int $grupoId
    ): bool {
        $db = Database::conectar();

        $stmt = $db->prepare(
            'SELECT COUNT(*)
             FROM emprestimos
             WHERE usuario_id = :usuario_id
               AND grupo_id = :grupo_id
               AND status IN ("PENDENTE","APROVADO")'
        );

        $stmt->execute([
            ':usuario_id' => $usuarioId,
            ':grupo_id'   => $grupoId
        ]);

        return (int) $stmt->fetchColumn() > 0;
    }

    /**
     * Lista empréstimos do grupo com paginação
     */
    public static function listarPorGrupoPaginado(
        int $grupoId,
        int $limite,
        int $offset
    ): array {
        $db = Database::conectar();

        $stmt = $db->prepare(
            'SELECT
                e.id AS emprestimo_id,
                e.usuario_id,
                e.valor_solicitado,
                e.valor_total,
                e.status,
                e.data_vencimento,
                u.nome
             FROM emprestimos e
             INNER JOIN usuarios u ON u.id = e.usuario_id
             WHERE e.grupo_id = :grupo_id
             ORDER BY e.data_solicitacao DESC
             LIMIT :limite OFFSET :offset'
        );

        $stmt->bindValue(':grupo_id', $grupoId, \PDO::PARAM_INT);
        $stmt->bindValue(':limite', $limite, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Lista empréstimos por status (ex: ATRASADO)
     */
    public static function listarPorStatus(
        int $grupoId,
        string $status
    ): array {
        $db = Database::conectar();

        $stmt = $db->prepare(
            'SELECT
                e.id AS emprestimo_id,
                e.usuario_id,
                e.valor_solicitado,
                e.valor_total,
                e.status,
                e.data_vencimento,
                u.nome
             FROM emprestimos e
             INNER JOIN usuarios u ON u.id = e.usuario_id
             WHERE e.grupo_id = :grupo_id
               AND e.status = :status'
        );

        $stmt->execute([
            ':grupo_id' => $grupoId,
            ':status'   => $status
        ]);

        return $stmt->fetchAll();
    }

    /**
     * Atualiza status do empréstimo
     */
    public static function atualizarStatus(
        int $emprestimoId,
        string $status,
        ?string $dataVencimento
    ): void {
        $db = Database::conectar();

        $stmt = $db->prepare(
            'UPDATE emprestimos
             SET status = :status,
                 data_aprovacao = NOW(),
                 data_vencimento = :data_vencimento
             WHERE id = :id'
        );

        $stmt->execute([
            ':status'          => $status,
            ':data_vencimento' => $dataVencimento,
            ':id'              => $emprestimoId
        ]);
    }

    /**
     * Atualiza empréstimo para atraso
     */
    public static function marcarComoAtrasado(
        int $emprestimoId,
        float $jurosAplicados
    ): void {
        $db = Database::conectar();

        $stmt = $db->prepare(
            'UPDATE emprestimos
             SET status = "ATRASADO",
                 juros_aplicados = :juros
             WHERE id = :id'
        );

        $stmt->execute([
            ':juros' => $jurosAplicados,
            ':id'    => $emprestimoId
        ]);
    }

    /**
     * Conta quantos empréstimos existem em um grupo
     * Usado para paginação
     */
    public static function contarPorGrupo(int $grupoId): int
    {
        $db = Database::conectar();

        $stmt = $db->prepare(
            'SELECT COUNT(*)
             FROM emprestimos
             WHERE grupo_id = :grupo_id'
        );

        $stmt->execute([
            ':grupo_id' => $grupoId
        ]);

        return (int) $stmt->fetchColumn();
    }
}