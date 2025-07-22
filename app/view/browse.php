<h1>Constellations</h1>
<span>There are 88 recognized constellations</span>
<ul>
    <?php foreach ($constellations as $constellation): ?>
        <a href=<?= "/constellation?name={$constellation["lowercaseName"]}" ?>>
            <h2><?= $constellation["name"] ?></h2>
            <div>
                <span>Main star: <?= $constellation["mainStar"] ?></span>
                <span><?= ucfirst($constellation["hemisphere"]) ?> Hemisphere</span>
            </div>
            <div>
                <p><?= $constellation["story"] ?></p>
                <img src=<?= $constellation["headerPictureSrc"] ?> alt=<?= "{$constellation["name"]} header image" ?>>
            </div>
        </a>
    <?php endforeach; ?>
</ul>