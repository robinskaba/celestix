<?php

function displayErrors($errors, $field) {
    if (!empty($errors[$field])) {
        echo "<div class=errors>";
        foreach ($errors[$field] as $error) {
            echo '<span>' . htmlspecialchars($error) . '</span>';
        }
        echo "</div>";
    }
}

?>