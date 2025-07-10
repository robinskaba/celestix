<?php 

require __DIR__ . '/util/form_errors.php';

?>

<h1>Register</h1>
<form method="POST" action="/register" enctype="multipart/form-data">
    <label>
        Username
        <input type="text" name="username" value="<?= htmlspecialchars($username) ?>" required>
    </label>
    <?php displayErrors($errors, "username"); ?>

    <label>
        Profile picture
        <input type="file" class="btn" name="profile-img">
    </label>
    <?php displayErrors($errors, "profile-img"); ?>

    <label>
        Email
        <input type="email" name="email" required value="<?= htmlspecialchars($email) ?>">
    </label>
    <?php displayErrors($errors, "email"); ?>

    <label>
        Password
        <input type="password" name="password" required>
    </label>
    <?php displayErrors($errors, "password"); ?>

    <label>
        Reenter password
        <input type="password" name="password-check" required>
    </label>
    <?php displayErrors($errors, "password-check"); ?>

    <button type="submit" class="btn">Register</button>
</form>
