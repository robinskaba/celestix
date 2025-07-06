<div class="vflex">
    <div id="info">
        <h1><?= htmlspecialchars($targetUsername) ?></h1>
        <img src=<?= $profile_img_path ?> alt=<?= htmlspecialchars($targetUsername)." profile picture" ?>>
    </div>

    <?php if ($ownsProfile): ?>
        <div id="actions">
            <a href="/change-password" class="btn">Change password</a>
        </div>
    <?php endif; ?>
</div>
<div id="stats">
    <?php foreach($stats as $stat): ?>
    <div class="stat">
        <h2><?= $stat["name"] ?></h2>
        <div>
            <span><?= $stat["success"]?>/<?= $stat["total"] ?></span>
            <span><?= "{$stat['percentage']}%" ?></span>
        </div>
    </div>
    <?php endforeach; ?>
</div>