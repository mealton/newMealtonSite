<?php
/**
 * Created by PhpStorm.
 * User: Юрчик
 * Date: 08.03.2020
 * Time: 21:22
 */

$css = array();
$cssParse = explode(";", $style);
foreach ($cssParse as $item) {
    $prop = explode(":", $item);
    if (trim($prop[0]) && trim($prop[1]))
        $css[trim($prop[0])] = trim($prop[1]);
}

session_start();
switch ($field) {
    case ('image'):
        ?>
        <div class="edit-item">
            <div class="controls">
                <fieldset>
                    <div class="flex" style="justify-content: flex-end;">
                        <input type="checkbox" name="image_default" class="fa fa-checkbox"
                               data-value="<?= $value ?>"
                               title="Сделать изображением по умолчанию"
                            <?= $_SESSION['publication']['image_default'] == $value ? 'checked' : '' ?>
                        >
                        <i class="fa fa-pencil-square-o" aria-hidden="true" data-id="<?= $k ?>" data-view="image_field"
                           title="Редактировать" onclick="Profile.editItem(this)"></i>
                        <i class="fa fa-times" aria-hidden="true" data-id="<?= $k ?>" title="Удалить"></i>
                        <input type="checkbox" name="multi-remove" value="<?= $k ?>" title="удалить несколько">
                    </div>
                </fieldset>
            </div>


            <a href="<?= str_replace('preview', 'fullsize', $value) ?>" title="Открыть изображение в новой вкладке"
               target="_blank"><img src="<?= str_replace('preview', 'fullsize', $value) ?>" alt="#"></a>
            <span class="hidden value-content"><?= $value ?></span>
            <br>
            <select name="insert_after" data-id="<?= $k ?>">
                <option value="" selected disabled>Вставить после</option>
                <option value="image_field">Картинку</option>
                <option value="video_field">Видео</option>
                <option value="subtitle_field">Подзаголовок</option>
                <option value="description_field">Описание</option>
                <option value="text_field">Текст</option>
            </select>
        </div>
        <?php
        break;
    case ('text'):
        ?>
        <div class="edit-item" style="<?= $style ?>">
            <div class="controls">
                <fieldset>
                    <div class="flex">
                        <?php include 'styles.php' ?>

                        <select name="field" class="form-control field" data-id="<?= $k ?>">
                            <option value="subtitle">Подзаголовок</option>
                            <option value="description">Описание</option>
                            <option value="text" selected>Текст</option>
                        </select>
                        <i class="fa fa-pencil-square-o" aria-hidden="true" data-id="<?= $k ?>" data-view="text_field"
                           title="Редактировать" onclick="Profile.editItem(this)"></i>
                        <i class="fa fa-times" aria-hidden="true" data-id="<?= $k ?>" title="Удалить"></i>
                        <input type="checkbox" name="multi-remove" value="<?= $k ?>" title="удалить несколько">
                    </div>

                </fieldset>
            </div>
            <p><?= $value ?></p>
            <span class="hidden value-content"><?= $value ?></span>
            <select name="insert_after" data-id="<?= $k ?>">
                <option value="" selected disabled>Вставить после</option>
                <option value="image_field">Картинку</option>
                <option value="video_field">Видео</option>
                <option value="subtitle_field">Подзаголовок</option>
                <option value="description_field">Описание</option>
                <option value="text_field">Текст</option>
            </select>
        </div>
        <?php
        break;
    case ('video'):
        ?>
        <div class="edit-item">
            <div class="controls">
                <fieldset>
                    <div class="flex" style="justify-content: flex-end;">
                        <i class="fa fa-pencil-square-o" aria-hidden="true" data-id="<?= $k ?>" data-view="video_field"
                           title="Редактировать" onclick="Profile.editItem(this)"></i>
                        <i class="fa fa-times" aria-hidden="true" data-id="<?= $k ?>" title="Удалить"></i>
                        <input type="checkbox" name="multi-remove" value="<?= $k ?>" title="удалить несколько">
                    </div>
                </fieldset>
            </div>
            <div id="video-preview<?= ++$i ?>" data-plyr-provider="youtube"
                 data-plyr-embed-id="<?= end(explode("/", $value)) ?>"></div>
            <span class="hidden value-content"><?= $value ?></span>
            <script>
                const Plyr<?=$i?> = new Plyr('#video-preview<?=$i?>');
            </script>
            <br>
            <select name="insert_after" data-id="<?= $k ?>">
                <option value="" selected disabled>Вставить после</option>
                <option value="image_field">Картинку</option>
                <option value="video_field">Видео</option>
                <option value="subtitle_field">Подзаголовок</option>
                <option value="description_field">Описание</option>
                <option value="text_field">Текст</option>
            </select>
        </div>
        <?php
        break;
    case ('subtitle'):
        ?>
        <div class="edit-item" style="<?= $style ?>">
            <div class="controls">
                <fieldset>

                    <div class="flex">
                        <?php include 'styles.php' ?>

                        <select name="field" class="form-control field" data-id="<?= $k ?>">
                            <option value="subtitle" selected>Подзаголовок</option>
                            <option value="description">Описание</option>
                            <option value="text">Текст</option>
                        </select>
                        <i class="fa fa-pencil-square-o" aria-hidden="true" data-id="<?= $k ?>"
                           data-view="subtitle_field"
                           title="Редактировать" onclick="Profile.editItem(this)"></i>
                        <i class="fa fa-times" aria-hidden="true" data-id="<?= $k ?>" title="Удалить"></i>
                        <input type="checkbox" name="multi-remove" value="<?= $k ?>" title="удалить несколько">
                    </div>


                </fieldset>

            </div>
            <h3><?= $value ?></h3>
            <span class="hidden value-content"><?= $value ?></span>
            <br>
            <select name="insert_after" data-id="<?= $k ?>">
                <option value="" selected disabled>Вставить после</option>
                <option value="image_field">Картинку</option>
                <option value="video_field">Видео</option>
                <option value="subtitle_field">Подзаголовок</option>
                <option value="description_field">Описание</option>
                <option value="text_field">Текст</option>
            </select>
        </div>
        <?php
        break;
    case ('description'):
        ?>
        <div class="edit-item" style="<?= $style ?>">
            <div class="controls">
                <fieldset>

                    <div class="flex">
                        <?php include 'styles.php' ?>

                        <select name="field" class="form-control field" data-id="<?= $k ?>">
                            <option value="subtitle">Подзаголовок</option>
                            <option value="description" selected>Описание</option>
                            <option value="text">Текст</option>
                        </select>
                        <i class="fa fa-pencil-square-o" aria-hidden="true" data-id="<?= $k ?>"
                           data-view="description_field"
                           title="Редактировать" onclick="Profile.editItem(this)"></i>
                        <i class="fa fa-times" aria-hidden="true" data-id="<?= $k ?>" title="Удалить"></i>
                        <input type="checkbox" name="multi-remove" value="<?= $k ?>" title="удалить несколько">
                    </div>


                </fieldset>

            </div>
            <h6><i><?= $value ?></i></h6>
            <span class="hidden value-content"><?= $value ?></span>
            <br>
            <select name="insert_after" data-id="<?= $k ?>">
                <option value="" selected disabled>Вставить после</option>
                <option value="image_field">Картинку</option>
                <option value="video_field">Видео</option>
                <option value="subtitle_field">Подзаголовок</option>
                <option value="description_field">Описание</option>
                <option value="text_field">Текст</option>
            </select>
        </div>
        <?php
        break;
   }
?>


