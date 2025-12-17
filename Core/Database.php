<?php

namespace Core;

use PDO;
use PDOException;

// Classe responsável pela conexão com o banco
class Database
{
    // Guarda a conexão ativa
    private static $connection;

    // Retorna a conexão (Singleton)
    public static function getConnection()
    {
        // Se ainda não existir conexão
        if (!self::$connection) {

            // Carrega as configurações
            $config = require __DIR__ . '/../config/database.php';

            try {
                // Cria o DSN (string de conexão)
                $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";

                // Cria a conexão PDO
                self::$connection = new PDO(
                    $dsn,
                    $config['user'],
                    $config['password']
                );

                // Define modo de erro como exceção
                self::$connection->setAttribute(
                    PDO::ATTR_ERRMODE,
                    PDO::ERRMODE_EXCEPTION
                );

            } catch (PDOException $e) {
                // Se der erro, para tudo e mostra mensagem
                die("Erro de conexão: " . $e->getMessage());
            }
        }

        // Retorna a conexão
        return self::$connection;
    }
}