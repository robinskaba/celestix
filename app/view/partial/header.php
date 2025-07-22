<header>
    <nav>
        <div>
            <a href="/" id="page-title">Celestix</a>
            <div class="page-links">
                <a href="/sky-guess">Sky Guess</a>
                <a href="/name-guess">Name Guess</a>
                <a href="/browse">Browse</a>
            </div>
        </div>
        <div class="page-links">
            <?php if ($loggedIn): ?>
                <a href=<?= "/profile?username=".htmlspecialchars($username) ?>>Profile</a>
                <a href="/log-out">Log out</a>
            <?php else: ?>
                <a href="/login">Login</a>
                <a href="/register">Register</a>
            <?php endif; ?>
        </div>
    </nav>
</header>