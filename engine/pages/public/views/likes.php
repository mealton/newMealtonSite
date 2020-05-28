<?php
/**
 * Created by PhpStorm.
 * User: Юрчик
 * Date: 21.03.2020
 * Time: 14:15
 */
?>

<i class="fa fa-thumbs-o-down" data-like="dislike" data-id="<?= $publication_id ?>" aria-hidden="true"></i>
<sub class="num"><?= $dislikes ?></sub>

<span id="likes-counter"><?= $likes - $dislikes ?></span>

<i class="fa fa-thumbs-o-up" data-like="like" data-id="<?= $publication_id ?>" aria-hidden="true"></i>
<sub class="num"><?= $likes ?></sub>


