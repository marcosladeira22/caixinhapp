<?php
namespace Models;

use Core\Database;

class Emprestimo
{

    /**
     * Cria uma solicitação de empréstimo
     */
    public static function criar(array $dados): int
    {
        $db = Database::conectar();

        $sql = "INSERT INTO emprestimos
                (usuario_id, grupo_id, valor_solicitado, taxa_aplicada, valor_total)
                VALUES
                (:usuario_id, :grupo_id, :valor_solicitado, :taxa_aplicada, :valor_total)";

        $stmt = $db->prepare($sql);
        $stmt->execute($dados);

        return $db->lastInsertId();
    }

    /**
     * Verifica se o usuário possui empréstimo em aberto
     */
    public static function possuiEmprestimoAberto(int $usuario_id, int $grupo_id): bool
    {
        $db = Database::conectar();

        $sql = "SELECT COUNT(*) FROM emprestimos
                WHERE usuario_id = :usuario_id
                AND grupo_id = :grupo_id
                AND status IN ('PENDENTE','APROVADO')";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':usuario_id' => $usuario_id,
            ':grupo_id' => $grupo_id
        ]);

        return $stmt->fetchColumn() > 0;
    }

    /**
     * Lista empréstimos pendentes de um grupo
     */
    public static function listarPendentesPorGrupo(int $grupo_id): array
    {
        $db = Database::conectar();

        $sql = "SELECT e.*, u.nome AS nome_usuario
                FROM emprestimos e
                JOIN usuarios u ON u.id = e.usuario_id
                WHERE e.grupo_id = :grupo_id
                AND e.status = 'PENDENTE'
                ORDER BY e.data_solicitacao ASC";

        $stmt = $db->prepare($sql);
        $stmt->execute([':grupo_id' => $grupo_id]);

        return $stmt->fetchAll();
    }

    /**
     * Atualiza status do empréstimo
     */
    public static function atualizarStatus(int $emprestimo_id, string $status, ?string $data_vencimento = null): void
    {
        $db = Database::conectar();

        $sql = "UPDATE emprestimos
                SET status = :status,
                    data_aprovacao = NOW(),
                    data_vencimento = :data_vencimento
                WHERE id = :id";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':status'          => $status,
            ':data_vencimento' => $data_vencimento,
            ':id'              => $emprestimo_id
        ]);
    }

    /**
     * Atualiza empréstimo para atraso
     */
    public static function atualizarAtraso(int $emprestimo_id, float $juros): void
    {
        $db = Database::conectar();

        $sql = "UPDATE emprestimos
                SET status = 'ATRASADO',
                    juros_aplicados = :juros
                WHERE id = :id";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':juros' => $juros,
            ':id' => $emprestimo_id
        ]);
    }

    /**
     * 
     */
    public static function listarPorGrupo(int $grupo_id): array
    {
        $db = \Core\Database::conectar();

        $sql = "SELECT e.*, u.nome
                FROM emprestimos e
                JOIN usuarios u ON u.id = e.usuario_id
                WHERE e.grupo_id = :grupo_id
                ORDER BY e.data_solicitacao DESC";

        $stmt = $db->prepare($sql);
        $stmt->execute([':grupo_id' => $grupo_id]);

        return $stmt->fetchAll();
    }

    /**
     * 
     */
    public static function listarPorStatus(int $grupo_id, string $status): array
    {
        $db = \Core\Database::conectar();

        $sql = "SELECT e.*, u.nome
                FROM emprestimos e
                JOIN usuarios u ON u.id = e.usuario_id
                WHERE e.grupo_id = :grupo_id
                AND e.status = :status";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':grupo_id' => $grupo_id,
            ':status' => $status
        ]);

        return $stmt->fetchAll();
    }

    /**
     * Lista empréstimos do grupo com paginação
     */
    public static function listarPorGrupoPaginado(int $grupo_id, int $limite, int $offset): array
    {
        $db = \Core\Database::conectar();

        $sql = "SELECT
                e.id AS emprestimo_id,
                e.usuario_id,
                e.valor_solicitado,
                e.valor_total,
                e.status,
                e.data_vencimento,
                u.nome
            FROM emprestimos e
            JOIN usuarios u
            ON u.id = e.usuario_id
            WHERE e.grupo_id = :grupo_id
            ORDER BY e.data_solicitacao DESC
            LIMIT :limite OFFSET :offset
        ";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':grupo_id', $grupo_id, \PDO::PARAM_INT);
        $stmt->bindValue(':limite', $limite, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Conta quantos empréstimos existem em um grupo
     * Usado para paginação
     */
    public static function contarPorGrupo(int $grupo_id): int
    {
        $db = \Core\Database::conectar();

        $stmt = $db->prepare("SELECT COUNT(*)
                    FROM emprestimos
                    WHERE grupo_id = :grupo_id
                ");

        $stmt->execute([
            ':grupo_id' => $grupo_id
        ]);

        return (int) $stmt->fetchColumn();
    }
}