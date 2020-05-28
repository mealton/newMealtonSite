<?php
/**
 * Created by PhpStorm.
 * User: Юрчик
 * Date: 14.02.2020
 * Time: 20:41
 */
?>

<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-body">
                <div class="media-block">
                    <a class="media-left" href="#"><img class="img-circle img-sm" alt="Профиль пользователя"
                                                        src="<?= $pic ? $pic : 'https://bootstraptema.ru/snippets/icons/2016/mia/1.png' ?>"></a>
                    <div class="media-body">
                        <div class="mar-btm">
                            <a href="#" class="btn-link text-semibold media-heading box-inline"><?= $username ?></a>
                            <p class="text-muted text-sm"><i class="fa fa-mobile fa-lg"></i> - <?= $com_date ?></p>
                        </div>
                        <p><?= $com_text ?></p>
                        <div class="pad-ver">
                            <div class="btn-group" data-id="<?= $id ?>">
                                <a class="btn btn-sm btn-default btn-hover-success comment-like" href="#"><i
                                        class="fa fa-thumbs-up"></i> <span
                                        class="likes-counter"><?= $likes ?></span></a>
                                <a class="btn btn-sm btn-default btn-hover-danger comment-like" href="#"><i
                                        class="fa fa-thumbs-down"></i> <span
                                        class="likes-counter"><?= $dislikes ?></span></a>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
