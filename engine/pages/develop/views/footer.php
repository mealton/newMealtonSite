<?php
/**
 * Created by PhpStorm.
 * User: Юрчик
 * Date: 04.02.2020
 * Time: 19:05
 * @var $import string
 * @var $post_id int
 * @var $likes int
 */

?>

<span class="import">
    <a href="<?= $import ?>" target="_blank"><?= main_Model::getImportLink($import) ?></a>
</span>
<span class="likes">
    <i class="fa fa-thumbs-o-up" aria-hidden="true"></i> <span id="likes-counter"><?= $likes ?></span>
</span>
<hr>
<div class="comment-form">
    <form action="" id="comment-form">
        <div class="textarea">
            <input type="hidden" name="post_id" value="<?= $post_id ?>">
            <input type="hidden" name="username" value="">
            <textarea name="comment" placeholder="Текст комментария" class="form-control"></textarea>
        </div>
        <div class="submit">
            <div class="media">
                <button type="button" class="add-picture"><i class="fa fa-camera" aria-hidden="true"></i></button>
                <div class=" upload hidden">
                    <input type="text" name="image-url" placeholder="url картинки" class="form-control upload-url">
                    <button type="button" class="upload-url-btn btn btn-info">
                        <i class="fa fa-upload" aria-hidden="true"></i>
                        <img src="/assets/img/ajax-loader.gif">
                    </button>
                    <label class="upload-label">
                        <input type="file" value="Загрузить с компьютера" class="hidden file-upload-input" multiple accept="image/x-png, image/gif, image/jpeg">
                        <span class="btn btn-primary">Загрузить с компьютера</span>
                        <img src="/assets/img/ajax-loader.gif">
                    </label>
                </div>
                <div class="preview"></div>
            </div>
            <button type="submit" class="btn btn-primary">Отправить</button>
        </div>
    </form>
</div>
