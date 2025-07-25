<h1>Constellations</h1>
<span>There are 88 recognized constellations</span>
<ul>
    <?php foreach ($constellations as $constellation): ?>
        <a href=<?= "/constellation?id={$constellation["id"]}" ?>>
            <h2><?= $constellation["name"] ?></h2>
            <div>
                <span>Main star: <?= $constellation["mainStar"] ?></span>
                <span><?= ucfirst($constellation["hemisphere"]) ?> Hemisphere</span>
            </div>
            <div>
                <p><?= $constellation["about"] ?></p>
                <img src=<?= $constellation["headerPictureSrc"] ?> alt=<?= "{$constellation["name"]} header image" ?>>
            </div>
        </a>
    <?php endforeach; ?>
</ul>
<div id="paging">
    <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 ?>">&lt;</a>
    <?php endif; ?>

    <?php
    $start = max(1, $page - 2);
    $end = min($totalPages, $page + 2);
    for ($i = $start; $i <= $end; $i++): ?>
        <a href="?page=<?= $i ?>"<?= $i === $page ? ' class="selected"' : '' ?>><?= $i ?></a>
    <?php endfor; ?>

    <?php if ($page < $totalPages): ?>
        <a href="?page=<?= $page + 1 ?>">&gt;</a>
    <?php endif; ?>
</div>