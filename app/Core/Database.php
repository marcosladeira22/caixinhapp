<?php

namespace Core;

use PDO;

class Database
{
    private static $instancia;

    public static function conectar()
    {
        if (!self::$instancia) {
            $config = require __DIR__ . '/../../config/banco.php';

            $dsn = "mysql:host={$config['host']};dbname={$config['banco']};charset={$config['charset']}";

            self::$instancia = new PDO(
                $dsn,
                $config['usuario'],
                $config['senha'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        }

        return self::$instancia;
    }
}