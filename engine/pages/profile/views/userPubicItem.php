<?php
/**
 * Created by PhpStorm.
 * User: Юрчик
 * Date: 20.03.2020
 * Time: 10:39
 */
?>


<div class="user-public-item <?= $status == 'deleted' ? 'deleted' : '' ?>" id="public-item-<?=$id?>">
        <div class="clearfix">
            <img
                src="<?= $image_default ? str_replace('fullsize', 'preview', $image_default) : str_replace('fullsize', 'preview', $random_img) ?>"
                alt=""
                class="float-left"/>
            <fieldset class="float-left">
                <h4><?= $short_title ?></h4>
                <p><?= $description ?></p>
                <h6><i><?= main_Controller::dateRusFormat($created_on) ?></i></h6>
                <p class="edit-publication">
                    <button type="button" class="btn btn-default edit-publication">
                        <a href="/profile/<?= $id . '::' . $alias ?>">Редактировать</a>
                    </button>
                    <button type="button" class="btn btn-danger delete-publication" data-id="<?= $id ?>">Удалить
                    </button>
                    <?php if ($status != 'deleted'): ?>
                        <button type="button" class="btn btn-primary unpublished" data-id="<?= $id ?>">Снять с
                            публикации
                        </button>
                        <a href="/public/<?= $id . '::' . $alias ?>">
                            <button type="button" class="btn btn-success"><b>Посмотреть</b></button>
                        </a>
                    <?php else: ?>
                        <button type="button" class="btn btn-primary published" data-id="<?= $id ?>">Опубликовать
                        </button>
                    <?php endif ?>


                </p>
            </fieldset>
        </div>
        <hr>
</div>
