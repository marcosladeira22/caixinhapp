<?php
namespace Services;

use Core\Database;
use Models\Grupo;
use Models\Emprestimo;
use Models\GrupoUsuario;
use DateTime;

class InadimplenciaService
{
    /**
     * Processa inadimplência de todos os empréstimos vencidos
     * Esse método pode ser chamado sempre que o usuário acessa o sistema
     */
    public static function processar(): void
    {
        $db = Database::conectar();

        // Busca empréstimos vencidos e ainda não pagos
        $sql = "SELECT * FROM emprestimos
                WHERE status = 'APROVADO'
                AND data_vencimento < CURDATE()";

        $stmt        = $db->query($sql);
        $emprestimos = $stmt->fetchAll();

        foreach ($emprestimos as $emprestimo) {
            self::processarEmprestimo($emprestimo);
        }
    }

    /**
     * Processa um empréstimo individualmente
     */
    private static function processarEmprestimo(array $emprestimo): void
    {
        $hoje = new DateTime();
        $vencimento = new DateTime($emprestimo['data_vencimento']);

        // Dias em atraso
        $diasAtraso = $vencimento->diff($hoje)->days;

        if ($diasAtraso <= 0) {
            return;
        }

        // Busca regras do grupo
        $grupo = Grupo::buscarPorId($emprestimo['grupo_id']);

        // Calcula juros por atraso
        $juros = self::calcularJuros($emprestimo['valor_total'], $grupo, $diasAtraso);

        // Atualiza empréstimo
        Emprestimo::atualizarAtraso($emprestimo['id'], $juros);

        // Penaliza score
        self::penalizarScore($emprestimo['usuario_id'], $emprestimo['grupo_id'], $diasAtraso);
    }

    /**
     * Calcula juros por atraso
     */
    private static function calcularJuros(
        float $valor,
        array $grupo,
        int $diasAtraso
    ): float
    {
        if ($grupo['juros_tipo'] === 'fixo') {
            return $grupo['juros_valor'] * $diasAtraso;
        }

        // Percentual ao dia
        return ($valor * $grupo['juros_valor'] / 100) * $diasAtraso;
    }

    /**
     * Penaliza o score do usuário inadimplente
     */
    private static function penalizarScore(
        int $usuario_id,
        int $grupo_id,
        int $diasAtraso
    ): void
    {
        $db = Database::conectar();

        // Penalidade progressiva
        $penalidade = min(30, $diasAtraso * 2);

        $sql = "UPDATE grupos_usuarios
                SET score = GREATEST(score - :penalidade, 0)
                WHERE usuario_id = :usuario_id
                AND grupo_id = :grupo_id";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':penalidade' => $penalidade,
            ':usuario_id' => $usuario_id,
            ':grupo_id'   => $grupo_id
        ]);
    }
}