<?php
/**
 * @var $username string
 * @var $com_text string
 * @var $images string
 * @var $video string
 * @var $com_date string
 * @var $replies string
 * @var $user_pic string
 * @var $likes int
 * @var $dislikes int
 * @var $id int
 */
$id = 'player' . rand(0, 1000);
?>
<div class="row comment">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-body">
                <div class="media-block">
                    <a class="media-left" href="#"><img class="img-circle img-sm" alt="Профиль пользователя"
                                                        src="<?= $profile_image ? $profile_image : 'https://bootstraptema.ru/snippets/icons/2016/mia/1.png' ?>"></a>
                    <div class="media-body">
                        <div class="mar-btm">
                            <a href="#" class="btn-link text-semibold media-heading box-inline"><?= $username ?></a>
                            <p class="text-muted text-sm"><i class="fa fa-mobile fa-lg"></i> - <?= main_Controller::dateTimeRusFormat($date)  ?></p>
                        </div>
                        <p><?= $comment ?></p>
                        <?php if ($video): ?>
                            <div class="comment-video" id="<?= ++$id ?>" data-plyr-provider="youtube"
                                 data-plyr-embed-id="<?= end(explode("/", $video)); ?>"></div>
                        <?php endif; ?>
                        <?= $img ?>
                        <div class="pad-ver">
                            <div class="btn-group" data-id="<?= $id ?>">
                                <a class="btn btn-sm btn-default btn-hover-success comment-like" href="#"
                                   data-id="<?= $c_id ?>" data-post-id="<?= $post_id ?>"><i
                                        class="fa fa-thumbs-up"></i> <span
                                        class="likes-counter"><?= $likes ?></span></a>
                                <a class="btn btn-sm btn-default btn-hover-danger comment-like" href="#"
                                   data-id="<?= $c_id ?>" data-post-id="<?= $post_id ?>"><i
                                        class="fa fa-thumbs-down"></i> <span
                                        class="dislikes-counter"><?= $dislikes ?></span></a>
                            </div>
                            <a class="btn btn-sm btn-default btn-hover-primary comment-reply-btn" href="#"
                               data-id="<?= $c_id ?>">Ответить</a>
                        </div>
                        <div class="comment-reply" data-id="<?= $c_id ?>"></div>
                        <div class="comment-replies"><?= $replies ?></div>
                        <hr>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>
        const <?=$id?> =
        new Plyr('#<?=$id?>');
    </script>
</div>