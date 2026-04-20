<?php
namespace Models;

use Core\Database;

class Grupo
{
    // Cria um novo grupo
    public static function criar($dados)
    {
        $db = Database::conectar();

        $sql = "INSERT INTO grupos 
            (nome, valor_cota, emprestimo_min, emprestimo_max, taxa_tipo, taxa_valor, juros_tipo, juros_valor, dias_tolerancia)
            VALUES 
            (:nome, :valor_cota, :emprestimo_min, :emprestimo_max, :taxa_tipo, :taxa_valor, :juros_tipo, :juros_valor, :dias_tolerancia)";

        $stmt = $db->prepare($sql);
        $stmt->execute($dados);

        // Retorna o ID do grupo criado
        return $db->lastInsertId();
    }

    // Lista grupos de um usuário
    public static function listarPorUsuario($usuario_id)
    {
        $db = Database::conectar();

        $sql = "SELECT g.*, gu.nivel 
                FROM grupos g
                JOIN grupos_usuarios gu ON gu.grupo_id = g.id
                WHERE gu.usuario_id = :usuario_id";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':usuario_id', $usuario_id);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}