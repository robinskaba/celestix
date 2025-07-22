<?php

namespace config;

use PDO;
use PDOException;

class Database {
    public static function connect(): PDO {
        $dsn = sprintf(
            "pgsql:host=%s;port=%s;dbname=%s;",
            getenv('DB_HOST'),
            getenv('DB_PORT'),
            getenv('DB_NAME')
        );

        // $env = parse_ini_file(__DIR__ . '/../.env');
        
        // $dsn = sprintf(
        //     "pgsql:host=%s;port=%s;dbname=%s;",
        //     $env['DB_HOST'],
        //     $env['DB_PORT'],
        //     $env['DB_NAME']
        // );

        try {
            return new PDO($dsn, getenv('DB_USER'), getenv('DB_PASS'), [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            // return new PDO($dsn, $env['DB_USER'], $env['DB_PASS'], [
            //     PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            //     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // ]);
        } catch (PDOException $e) {
            die('Connection failed: ' . $e->getMessage());
        }
    }
}
