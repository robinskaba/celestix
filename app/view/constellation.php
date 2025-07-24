<img src=<?= $constellation["headerPictureSrc"] ?> alt=<?= "{$constellation["name"]} header img" ?>>
<div id="desc">
    <h1><?= $constellation["name"] ?></h1>
    <span><span class="bold">Main star</span>: <?= $constellation["mainStar"] ?></span>
    <span><span class="bold">Hemisphere</span>: <?= $constellation["hemisphere"] ?></span>
    <span><span class="bold">Symbolism</span>: <?= $constellation["symbolism"] ?></span>
</div>
<h2>About</h2>
<p><?= htmlspecialchars($constellation["about"]) ?></p>
<h2>History and mythology</h2>
<p><?= htmlspecialchars($constellation["story"]) ?></p>

<?php if($isAdmin): ?>
<a href=<?= "/update-constellation?id={$constellation['id']}" ?> class="btn" id="edit-btn">Edit</a>
<?php endif; ?>