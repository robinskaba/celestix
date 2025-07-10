<?php

require_once __DIR__ . "/util/form_errors.php";

?>

<h1>Reset password</h1>
<form action="/change-password" method="POST">
    <label>
        Old password
        <input type="password" name="old-password">
    </label>
    <?php displayErrors($errors, "old-password"); ?>

    <label>
        New Password
        <input type="password" name="new-password">
    </label>
    <?php displayErrors($errors, "new-password"); ?>

    <label>
        Reenter new password
        <input type="password" name="new-password-check">
    </label>
    <?php displayErrors($errors, "new-password-check"); ?>

    <button type="submit" class="btn">Change password</button>
</form>