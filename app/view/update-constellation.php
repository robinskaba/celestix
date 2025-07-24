<h1>Update <?= htmlspecialchars($name) ?></h1>
<form method="POST" action="/update-constellation" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">

    <label>
        Name
        <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" required>
    </label>

    <label>
        Main star
        <input type="text" name="main_star" required value="<?= htmlspecialchars($mainStar) ?>">
    </label>

    <label>
        Symbolism
        <input type="text" name="symbolism" required value="<?= htmlspecialchars($symbolism) ?>">
    </label>

    <label>
        Hemisphere
        <select name="hemisphere" required>
            <option value="">Select hemisphere</option>
            <option value="northern" <?= isset($hemisphere) && $hemisphere === 'northern' ? 'selected' : '' ?>>Northern</option>
            <option value="southern" <?= isset($hemisphere) && $hemisphere === 'southern' ? 'selected' : '' ?>>Southern</option>
            <option value="equatorial" <?= isset($hemisphere) && $hemisphere === 'equatorial' ? 'selected' : '' ?>>Equatorial</option>
        </select>
    </label>

    <div class="img-label-wrap">
        <label>
            Header picture
            <input type="file" class="btn" name=<?= "old#".($headerPictureId ?? -1) ?>>
        </label>
        <?php if ($headerPictureId != null): ?>
        <a href=<?= "/resources/image?id=".$headerPictureId ?> class="sqr btn" target="_blank">View</a>
        <?php endif; ?>
    </div>

    <label>
        About
        <textarea name="about" required><?= htmlspecialchars($about) ?></textarea>
    </label>

    <label>
        Story
        <textarea name="story" required><?= htmlspecialchars($story) ?></textarea>
    </label>

    <ul id="guess-pairs">
        <?php
            function addPair($pair, $count) {
                ?>

                <li <?= $pair == null ? "id=pair-prefab class=hidden" : "" ?>>
                    <h2>Pair <?= $count ?></h2>
                    <div class="img-label-wrap">
                        <label>
                            Clean picture
                            <input type="file" class="btn" name=<?= $pair != null ? ("old#".($pair["clean"])) : "new-clean" ?>>
                        </label>
                        <?php if ($pair != null): ?>
                        <a href=<?= "/resources/image?id=".$pair["clean"] ?> class="sqr btn" target="_blank">View</a>
                        <?php endif; ?>
                        <label>
                            Lines picture
                            <input type="file" class="btn" name=<?= $pair != null ? ("old#".($pair["lines"])) : "new-lines" ?>>
                        </label>
                        <?php if ($pair != null): ?>
                        <a href=<?= "/resources/image?id=".$pair["lines"] ?> class="sqr btn" target="_blank">View</a>
                        <?php endif; ?>
                    </div>
                </li>

                <?php
            }

            // template pair
            addPair(null, 0);

            $count = 1;
            foreach ($pairs as $pair) {
                addPair($pair, $count);
                $count++;
            }
        ?>
    </ul>

    <button type="button" class="btn" id="pair-add">Add guess pair</button>

    <hr>

    <button type="submit" class="btn">Update</button>
</form>
