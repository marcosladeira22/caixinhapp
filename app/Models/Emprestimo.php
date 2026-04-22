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
}