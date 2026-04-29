<?php
namespace Core;

use PDO;
use PDOException;
use RuntimeException;

/**
 * Gerenciador de conexão com o banco de dados
 */
class Database
{
    private static ?PDO $instancia = null;

    /**
     * Retorna instância única de PDO
     */
    public static function conectar(): PDO
    {
        if (self::$instancia === null) {

            $config = require __DIR__ . '/../../config/banco.php';

            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=%s',
                $config['host'],
                $config['banco'],
                $config['charset']
            );

            try {
                self::$instancia = new PDO(
                    $dsn,
                    $config['usuario'],
                    $config['senha'],
                    [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]
                );
            } catch (PDOException $e) {
                throw new RuntimeException('Erro ao conectar ao banco de dados.');
            }
        }

        return self::$instancia;
    }
}