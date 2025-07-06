<?php

use app\service\SessionService;

?>

<header>
    <nav>
        <div>
            <a href="/" id="page-title">Celestix</a>
            <div class="page-links">
                <a href="/sky-guess">SKY GUESSING</a>
                <a href="/name-guess">ALL NAMES GUESSING</a>
                <a href="/browse">Browse constellations</a>
            </div>
        </div>
        <div class="page-links">
            <?php if (SessionService::isLoggedIn()): ?>
                <a href=<?= "/profile?username=".htmlspecialchars(SessionService::getUser()->username) ?>>Profile</a>
                <a href="/log-out">Log out</a>
            <?php else: ?>
                <a href="/login">Login</a>
                <a href="/register">Register</a>
            <?php endif; ?>
        </div>
    </nav>
</header>