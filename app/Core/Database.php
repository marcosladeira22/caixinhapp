<?php
namespace Core;

use PDO;
use PDOException;

class Database
{
    private static $instancia;

    public static function conectar()
    {
        if (!self::$instancia) {

            // Carrega configurações do banco
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
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]
                );
            } catch (PDOException $e) {
                // Em desenvolvimento, mostramos o erro
                die('Erro de conexão: ' . $e->getMessage());
            }
        }

        return self::$instancia;
    }
}