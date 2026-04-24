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
    public static function atualizarStatus(
        int $emprestimo_id,
        string $status,
        ?string $data_vencimento = null
    ): void
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
    public static function atualizarAtraso(
        int $emprestimo_id,
        float $juros
    ): void
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
}