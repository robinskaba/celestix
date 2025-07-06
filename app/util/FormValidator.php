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
}