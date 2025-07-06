<img src=<?= $constellation["headerImg"] ?> alt=<?= "{$constellation["name"]} header img" ?>>
<div id="desc">
    <h1><?= $constellation["name"] ?></h1>
    <span>Main star: <?= $constellation["mainStar"] ?></span>
    <span>Hemisphere: <?= $constellation["hemisphere"] ?></span>
    <span>Symbolism: <?= $constellation["symbolism"] ?></span>
</div>
<p>
    <?= nl2br($constellation["story"]) ?>
</p>
