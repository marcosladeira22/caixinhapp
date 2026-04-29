<?php
namespace Models;

use Core\Database;

/**
 * Model Grupo
 * Responsável apenas por persistência de dados do grupo
 */
class Grupo
{
    /**
     * Cria um novo grupo
     */
    public static function criar(
        string $nome,
        float $valorCota,
        float $emprestimoMin,
        float $emprestimoMax,
        string $taxaTipo,
        float $taxaValor,
        string $jurosTipo,
        float $jurosValor,
        int $diasTolerancia
    ): int {
        $db = Database::conectar();

        $stmt = $db->prepare(
            'INSERT INTO grupos
             (nome, valor_cota, emprestimo_min, emprestimo_max,
              taxa_tipo, taxa_valor, juros_tipo, juros_valor, dias_tolerancia)
             VALUES
             (:nome, :valor_cota, :emprestimo_min, :emprestimo_max,
              :taxa_tipo, :taxa_valor, :juros_tipo, :juros_valor, :dias_tolerancia)'
        );

        $stmt->execute([
            ':nome'             => $nome,
            ':valor_cota'       => $valorCota,
            ':emprestimo_min'   => $emprestimoMin,
            ':emprestimo_max'   => $emprestimoMax,
            ':taxa_tipo'        => $taxaTipo,
            ':taxa_valor'       => $taxaValor,
            ':juros_tipo'       => $jurosTipo,
            ':juros_valor'      => $jurosValor,
            ':dias_tolerancia'  => $diasTolerancia,
        ]);

        return (int) $db->lastInsertId();
    }

    /**
     * Lista os grupos aos quais um usuário pertence
     */
    public static function listarPorUsuario(int $usuarioId): array
    {
        $db = Database::conectar();

        $stmt = $db->prepare(
            'SELECT g.*, gu.nivel
             FROM grupos g
             INNER JOIN grupos_usuarios gu ON gu.grupo_id = g.id
             WHERE gu.usuario_id = :usuario_id'
        );

        $stmt->execute([
            ':usuario_id' => $usuarioId
        ]);

        return $stmt->fetchAll();
    }

    /**
     * Busca um grupo pelo ID
     */
    public static function buscarPorId(int $grupoId): ?array
    {
        $db = Database::conectar();

        $stmt = $db->prepare(
            'SELECT *
             FROM grupos
             WHERE id = :id
             LIMIT 1'
        );

        $stmt->execute([
            ':id' => $grupoId
        ]);

        return $stmt->fetch() ?: null;
    }
}