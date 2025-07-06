<?php

namespace app\service;

use app\dao\UserDAO;
use app\model\User;

class SessionService {
    public static function start(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function login(string $username): void {
        self::start();
        session_regenerate_id(true);
        $_SESSION['username'] = $username;
    }

    public static function logout(): void {
        self::start();
        session_unset();
        session_destroy();
    }

    public static function getUser(): ?User {
        self::start();
        if (!isset($_SESSION['username'])) return null;

        $dao = new UserDAO();
        return $dao->findByUsername($_SESSION['username']);
    }

    public static function isLoggedIn(): bool {
        self::start();
        return isset($_SESSION['username']);
    }

    // sky guess
    public static function setField(string $field, string $value) {
        self::start();
        $_SESSION[$field] = $value;
    }

    public static function getField(string $field) {
        self::start();
        return $_SESSION[$field] ?? null;
    }
}
