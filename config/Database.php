<?php

namespace config;

use PDO;
use PDOException;

class Database {
    public static function connect(): PDO {
        $isProduction = getenv('APP_ENV') === 'production'; // set env val on prod server

        if ($isProduction) {
            $dsn = sprintf(
                "pgsql:host=%s;port=%s;dbname=%s;",
                getenv('DB_HOST'),
                getenv('DB_PORT'),
                getenv('DB_NAME')
            );
            $user = getenv('DB_USER');
            $pass = getenv('DB_PASS');
        } else {
            $env = parse_ini_file(__DIR__ . '/../.env');
            $dsn = sprintf(
                "pgsql:host=%s;port=%s;dbname=%s;",
                $env['DB_HOST'],
                $env['DB_PORT'],
                $env['DB_NAME']
            );
            $user = $env['DB_USER'];
            $pass = $env['DB_PASS'];
        }

        try {
            return new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            die('Connection failed: ' . $e->getMessage());
        }
    }
}
