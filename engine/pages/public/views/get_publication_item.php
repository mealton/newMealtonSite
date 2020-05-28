<?php
/**
 * @var $subtitle string
 * @var $image string
 * @var $image_description string
 * @var $video string
 * @var $video_description string
 * @var $text string
 */
?>

<div class="col">
    <?php if ($image): ?>
        <h3><?= $subtitle ?></h3>
    <?php endif; ?>
    <?php if ($image): ?>
        <div class="image-item">
            <?php if ($image_description): ?>
                <h4 class="image-description"><i><?= $image_description ?></i></h4>
            <?php endif; ?>
            <img src="<?= $image ?>" alt="<?= $image_description ?>" class="image">
        </div>
    <?php endif; ?>
    <?php if ($video): $id = 'player' . rand(0, 1000);?>
        <?php if ($video_description): ?>
            <h5><i><?= $video_description ?></i></h5>
        <?php endif; ?>
        <div id="<?=++$id?>" data-plyr-provider="youtube" data-plyr-embed-id="<?= end(explode("/", $video)); ?>"></div>
        <script>
            const <?=$id?> = new Plyr('#<?=$id?>');
        </script>
    <?php endif; ?>
    <?php if ($text): ?>
        <p><?= $text ?></p>
    <?php endif; ?>
</div>