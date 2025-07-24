<?php

namespace app\util;

class FormValidator {

    public static function isValid(array $errors): bool {
        // validating if form can pass (no errors detected)
        foreach ($errors as $fieldErrors) {
            if (!empty($fieldErrors)) {
                return false;
            }
        }
        return true;
    }

    public static function validateImageUpload(array $file, int $maxSizeMB): array {
        $errors = [];

        if (!empty($file) && isset($file["error"]) && $file["error"] !== UPLOAD_ERR_NO_FILE) {
            if ($file["error"] === UPLOAD_ERR_INI_SIZE || $file["error"] === UPLOAD_ERR_FORM_SIZE) {
                $errors[] = "Image is too large (server limit exceeded)";
            } elseif ($file["error"] !== UPLOAD_ERR_OK) {
                $errors[] = "Upload failed (error code: {$file["error"]})";
            } elseif ($file["size"] > $maxSizeMB * 1024 * 1024) {
                $errors[] = "Profile image size must be less than {$maxSizeMB}MB";
            }
        }

        return $errors;
    }

    public static function validateEmail(string $email): array {
        $errors = [];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }
        if (empty($email)) {
            $errors[] = "Email is required.";
        }

        return $errors;
    }
}