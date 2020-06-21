<?php
/**
 * Created by PhpStorm.
 * User: Юрчик
 * Date: 08.03.2020
 * Time: 10:35
 */
?>


<div class="user">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Мои публикации</a></li>
            <li><a href="#tabs-2">Добавить новую</a></li>
            <li><a href="#tabs-3">Профиль</a></li>
        </ul>
        <div id="tabs-1">
            <h3>Мои публикации</h3>
            <?=$getUserPublications?>
            <div class="container pagination align-content-center">
                <?= $pagination ?>
            </div>
        </div>
        <div id="tabs-2">
            <div class="new-publication">

                <form action="">

                    <h3 class="pointer clearfix">
                        Добавить новую публикацию
                        <label class="float-right">
                            <select name="category" class="form-control">
                                <option value="" selected disabled>Выберите категорию</option>
                                <?=$categories?>
                            </select>
                        </label>
                    </h3>

                    <?php include_once 'new_post_body.php'?>

                </form>

            </div>

        </div>

        <div id="tabs-3">
            <h3>Личные данные</h3>
            <fieldset>
                <?php if ($profile_image): ?>
                    <img src="<?= $profile_image ?>" alt="" class="profile-image">
                    <hr>
                <?php endif; ?>
                <div class="form-data">
                    <div class=" upload">
                        <input type="text" name="profile_image" placeholder="url картинки" class="form-control upload-url"
                               value="<?= $profile_image ?>">
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
                    <div class="left">
                        <button type="button" class="btn btn-primary update-profile-image" data-id="<?=$id?>">Изменить изображение</button>
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <legend><?= $name ? $name : $username ?></legend>
                <ul>
                    <li><strong class="name">email</strong>: <span class="value"><?= $email ?></span> <i
                            class="fa fa-pencil-square-o edit" aria-hidden="true"></i></li>
                    <li><strong class="name">Дата рождения</strong>: <span
                            class="value"><?= main_Controller::dateRusFormat($birthday) ?></span></li>
                    <li><strong class="name">На сайте</strong>: <span
                            class="value"><?= main_Controller::getPeriod($date_registrated) ?></span></li>
                </ul>
            </fieldset>
            <hr>
        </div>

    </div>

</div>

