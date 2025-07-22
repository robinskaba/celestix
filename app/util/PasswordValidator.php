<?php

namespace app\util;

class PasswordValidator {
    
    public static function validatePassword(string $password): array {
        $errors = [];

        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters long.";
        }
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = "Password must contain at least one number.";
        }
        if (!preg_match('/[\W_]/', $password)) {
            $errors[] = "Password must contain at least one special character.";
        }

        return $errors;
    }

    public static function validatePasswordConfirmation(string $password, string $passwordCheck): array {
        if ($password !== $passwordCheck) {
            return ["Passwords do not match."];
        }
        return [];
    }

}
