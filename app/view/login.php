<?php

require_once __DIR__ . "../util/form_errors.php";

?>

<h1>Login</h1>
<form action="/login" method="POST">
    <label>
        Username
        <input type="text" name="username">
    </label>
    <label>
        Password
        <input type="password" name="password">
    </label>

    <?php displayErrors($errors, "password") ?>

    <!-- <a href="/reset-password">I forgot my password</a> -->
    <button type="submit" class="btn">Login</button>
</form>
