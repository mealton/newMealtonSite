<?php if (in_array(trim(mb_strtolower($hashtag)), explode('/', $_GET['query']))): ?>

    <span class="hashtag-item active-tag">
        <span><?= $hashtag ?></span>
        <span><?= $count ?></span>
    </span>

<?php else : ?>

    <span class="hashtag-item">
        <a href="/hashtags/<?= trim(mb_strtolower($hashtag)) ?>">
            <span><?= $hashtag ?></span>
            <span><?= $count ?></span>
        </a>
    </span>

<?php endif; ?>