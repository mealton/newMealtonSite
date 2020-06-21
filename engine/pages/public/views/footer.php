<?php
/**
 * Created by PhpStorm.
 * User: Юрчик
 * Date: 04.02.2020
 * Time: 19:05
 * @var $import string
 * @var $hashtags string
 * @var $post_id int
 * @var $likes int
 */
session_start();
?>

<div class="likes-container">
    <span class="import">
        <?php if ($import): ?>
            <strong>Источник:</strong>&nbsp;<a href="<?= $import ?>"
                                               target="_blank"><?= main_Model::getImportLink($import) ?></a>
        <?php endif; ?>
    </span>
    <span class="likes" id="likes">
        <?php include 'likes.php' ?>
    </span>
</div>

<?php if ($hashtags): ?>
    <hr>
    <strong>Метки:</strong>&nbsp;<?= main_Model::getTagsString($hashtags) ?>
<?php endif; ?>

<hr>
<?php if (explode('/', $_GET['query'])[1] != 'preview'): ?>
    <a href="<?= $referer ?>#post-<?= $publication_id ?>" data-content="index"
       data-menu="<?= $_SESSION['config']['menu'] ?>" class="session-config">
        <button class="btn btn-info">Назад</button>
    </a>
<?php else: ?>
    <a href="/profile/" data-content="profile" class="session-config">
        <button class="btn btn-info">Назад</button>
    </a>
<?php endif; ?>
<?php if ($_SESSION['login'] === $username): ?>
    <a href="/profile/<?= $publication_id . '::' . $alias ?>" data-content="profile" class="session-config">
        <button class="btn btn-primary">Редактировать</button>
    </a>
<?php endif; ?>
<hr  id="comments">
<div class="neighbors-publics"><?= $prev_next_html ?></div>
<hr>
<div class="comment-form">
    <span class="profile-image" style="background-image: url('<?=$profile_image?>');" id="profile_image_comment"></span>
    <form action="" id="comment-form">
        <div class="textarea">
            <input type="hidden" name="post_id" value="<?= $publication_id ?>">
            <input type="hidden" name="user_id" value="<?= $user_id ?>">
            <textarea name="comment" placeholder="Текст комментария" class="form-control"></textarea>
        </div>
        <div class="submit">
            <div class="media">
                <button type="button" class="add-picture"><i class="fa fa-camera" aria-hidden="true"></i>
                </button>
                <div class=" upload hidden">
                    <input type="text" name="image-url" placeholder="url картинки" class="form-control upload-url">
                    <button type="button" class="upload-url-btn btn btn-info">
                        <i class="fa fa-upload" aria-hidden="true"></i>
                        <img src="/assets/img/ajax-loader.gif">
                    </button>
                    <label class="upload-label">
                        <input type="file" value="Загрузить с компьютера" class="hidden file-upload-input" multiple
                               accept="image/x-png, image/gif, image/jpeg">
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
<section class="container comments-block">
    <?= $comments ?>
</section>
