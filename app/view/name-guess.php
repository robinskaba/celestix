<h1>Can you guess the names of all the constellations?</h1>
<div>
    <span id="time">00:00</span>
    <form id=guess-form action="">
        <input type="text" name="guessed-name">
        <button type="submit">Enter</button>
    </form>
    <span id="guess-count">0/88</span>
</div>
<ul id="all-constellations">
    <?php for ($i = 1; $i <= 88; $i++): ?>
        <li class="constellation" id=<?= "const_{$i}" ?>><?php echo "{$i}."; ?></li>
    <?php endfor; ?>
</ul>