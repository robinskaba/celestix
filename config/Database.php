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

        try {
            return new PDO($dsn, getenv('DB_USER'), getenv('DB_PASS'), [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            die('Connection failed: ' . $e->getMessage());
        }
    }
}
