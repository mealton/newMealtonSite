<?php
/**
 * Created by PhpStorm.
 * User: Юрчик
 * Date: 28.05.2020
 * Time: 11:35
 */
?>

<a href="/public/<?= $public_id ?>::<?= $alias ?>" data-content="post" data-id="<?= $public_id ?>" class="sidebar-link">
    <div class="sidebar-public"
         style="background-image: url('<?= $image_default ? str_replace('preview', 'fullsize', $image_default) : str_replace('preview', 'fullsize', $random_img) ?>')">
        <div class="sidebar-public-inner">
             <span>
                <?= $long_title ? $long_title : $short_title ?> <?= main_Controller::mediaCounter($count_img, $count_video) ?>
            </span>
        </div>
    </div>
</a>
