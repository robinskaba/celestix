<?php

use app\service\SessionService;

$loggedIn = SessionService::isLoggedIn();
$username = $loggedIn ? SessionService::getUser()->username : "";

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title><?= htmlspecialchars($title ?? 'Celestix') ?></title>

        <link rel="shortcut icon" href="/assets/img/favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="/assets/css/shared.css">
        <link rel="stylesheet" href="/assets/css/header.css">
        <link rel="stylesheet" href="/assets/css/footer.css">

        <?php foreach ($css ?? [] as $href): ?>
            <link rel="stylesheet" href="<?= $href ?>">
        <?php endforeach; ?>

        <?php foreach ($scripts ?? [] as $script): ?>
            <script 
                src="<?= $script['src'] ?>"
                <?= !empty($script['defer']) ? 'defer' : '' ?>
                <?= !empty($script['async']) ? 'async' : '' ?>>
            </script>
        <?php endforeach; ?>
    </head>

    <body>
        <?php require __DIR__ . '/view/partial/header.php'; ?>

        <main>
            <?php require __DIR__ . "/view/$view.php"; ?>
        </main>

        <?php require __DIR__ . '/view/partial/footer.php'; ?>
    </body>
</html>
