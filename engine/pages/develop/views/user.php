<?php
/**
 * Created by PhpStorm.
 * User: Юрчик
 * Date: 07.03.2020
 * Time: 16:38
 * @var $status string
 * @var $isActive string
 * @var $profile_image string
 * @var $name string
 * @var $username string
 * @var $email string
 * @var $birthday string
 * @var $comment string
 * @var $date_deleted string
 * @var $id int
 */

?>

<li class="item <?= $isActive === "deleted" ? 'deleted' : '' ?>">

    <div class="pointer user-title" onclick="Admin.showDetails(this)">
        <?php if($profile_image):?>
            <!--<img src="<?/*= str_replace('fullsize', 'preview', $profile_image) */?>" alt="#" class="profile-image-mini">-->
            <div class="profile-image-mini" style="background-image: url('<?= str_replace('fullsize', 'preview', $profile_image) ?>');"></div>
        <?php endif;?>
        <span class="user-data"><?= $name ?> - <?= $username ?> <?= $status === "admin" ? '(админ)' : '' ?></span>
    </div>

    <div class="details hidden">
        <form>
            <div class="is-active">
                <?= $isActive === "deleted" ? '<span class="user-deleted-date">удален ' . $date_deleted . '</span>' : '' ?>
                <label>Активен <input type="checkbox" value="<?=$id?>" class="delete-user" <?= $isActive === "deleted" ? '' : 'checked' ?>></label>
            </div>
            <ul>
                <li><input type="text" class="form-control" name="username" value="<?= $username ?>"
                           placeholder="Имя пользователя"></li>
                <li><input type="email" class="form-control" name="email" value="<?= $email ?>" placeholder="Email">
                </li>
                <li><input type="text" class="form-control" name="name" value="<?= $name ?>" placeholder="Имя"></li>
                <li><input type="date" class="form-control" name="birthday" value="<?= $birthday ?>"
                           placeholder="Дата рождения"></li>
                <li>
                    <div class="form-data">
                        <div class=" upload">
                            <input type="text" name="profile_image" placeholder="url картинки"
                                   class="form-control upload-url" value="<?= $profile_image ?>">
                            <button type="button" class="upload-url-btn btn btn-info">
                                <i class="fa fa-upload" aria-hidden="true"></i>
                                <img src="/assets/img/ajax-loader.gif">
                            </button>
                            <label class="upload-label">
                                <input type="file" value="Загрузить с компьютера" class="hidden file-upload-input"
                                       accept="image/x-png, image/gif, image/jpeg">
                                <span class="btn btn-primary">Загрузить с компьютера</span>
                                <img src="/assets/img/ajax-loader.gif">
                            </label>
                        </div>
                        <div class="preview"></div>
                    </div>
                    <br>
                </li>
                <li>
                    <fieldset>
                        <legend>Права администратора</legend>
                        <label>Да<input type="radio" name="status"
                                        value="admin" <?= $status === "admin" ? 'checked' : '' ?>></label>
                        <label>Нет<input type="radio" name="status"
                                         value="user" <?= $status === "admin" ? '' : 'checked' ?>></label>
                    </fieldset>
                </li>
                <li>
                    <br>
                    <textarea name="comment" class="form-control" placeholder="Комментарий"><?= $comment ?></textarea>
                </li>
            </ul>
            <hr>
            <input type="hidden" name="id" value="<?= $id ?>">
            <button type="button" class="reset-password btn btn-default">Сбросить пароль</button>
            <button type="button" class="save-changes-user btn btn-primary">Сохранить изменения</button>
        </form>
    </div>

</li>
