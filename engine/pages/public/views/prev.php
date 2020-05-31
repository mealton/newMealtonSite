<?php
/**
 * Created by PhpStorm.
 * User: Юрчик
 * Date: 30.05.2020
 * Time: 13:10
 */

?>
<div class="prev-nav">
    <?php if ($public_id): ?>
        <h4><a href="/public/<?= $public_id ?>::<?= $alias ?>" title="<?=$description?>"><?= $long_title ? $long_title : $short__title ?><div class="preview"
                                                                                                                    style="background-image: url('<?= $image_default ? $image_default : $random_img ?>')"></div></a></h4>
    <?php endif; ?>
</div>
