<?php

session_start();

?>
    <style>
        /*.pagination{
            display: none !important;
        }*/
    </style>

    <div class="fields">
        <div class="import">
            <input type="text" name="imported" class="form-control import-input" placeholder="URL"
                   value="<?= $imported ?>">
            <button type="button" class="btn btn-primary import-public">
                Импотировать
                <img src="/assets/img/ajax-loader.gif">
            </button>
        </div>
        <hr>
        <input type="text" name="short_title" class="form-control title-input" placeholder="Короткий заголовок"
               value="<?= $short_title ?>">
        <hr>
        <input type="text" name="long_title" class="form-control title-input" placeholder="Длинный заголовок"
               value="<?= $long_title ?>">
        <hr>
        <textarea name="description" class="form-control title-input"
                  placeholder="Описание публикации"><?= $description ?></textarea>
        <hr>
        <input type="text" name="alias" class="form-control title-input" placeholder="Псевдоним" value="<?= $alias ?>">
        <hr>
        <input type="text" name="hashtags" class="form-control title-input" placeholder="Тэги" value="<?= $hashtags ?>">
        <hr>

        <div class="set-to-all-text-fields">
            <hr>
            <div class="controls">
                <fieldset style="background: #f2f2f2;">
                    <legend>Стилизация всех элементов</legend>
                    <div class="flex">

                        <div class="align">
                            <button type="button" class="btn align-btn style-all" data-prop="text-align" data-style="left"><i class="fa fa-align-left" aria-hidden="true"></i></button>
                            <button type="button" class="btn align-btn style-all" data-prop="text-align" data-style="center"><i class="fa fa-align-center" aria-hidden="true"></i></button>
                            <button type="button" class="btn align-btn style-all" data-prop="text-align" data-style="right"><i class="fa fa-align-right" aria-hidden="true"></i></button>
                            <button type="button" class="btn align-btn style-all" data-prop="text-align" data-style="justify"><i class="fa fa-align-justify" aria-hidden="true"></i></button>
                        </div>
                        <div class="font-style">
                            <button type="button" class="btn style-all" data-prop="font-weight" data-style="bold"><i class="fa fa-bold" aria-hidden="true"></i></button>
                            <button type="button" class="btn style-all" data-prop="font-style" data-style="italic"><i class="fa fa-italic" aria-hidden="true"></i></button>
                            <button type="button" class="btn style-all" data-prop="text-decoration" data-style="underline"><i class="fa fa-underline" aria-hidden="true"></i></button>
                        </div>
                        <div class="font-size">
                            <button type="button" class="btn style-all" data-prop="font-size" data-style="less"><i class="fa fa-caret-down" aria-hidden="true"></i></button>
                            <input type="number" class="form-control" name="set-font-size" value="16" min="14" max="36" readonly>
                            <button type="button" class="btn style-all" data-prop="font-size" data-style="larger"><i class="fa fa-caret-up" aria-hidden="true"></i></button>
                        </div>

                        <select name="all-fields" class="form-control field">
                            <option value="subtitle">Подзаголовок</option>
                            <option value="description">Описание</option>
                            <option value="text" selected>Текст</option>
                        </select>
                    </div>
                </fieldset>
            </div>

            <hr>


        </div>


        <div id="publication">
            <?= $publication ?>
        </div>
        <div class="clearfix">
            <fieldset class="float-right fields-select">
                <legend>Выберите действие:</legend>
                <div class="actions">
                <span class="action" title="Добавить картинку" data-view="image_field"><i class="fa fa-picture-o"
                                                                                          aria-hidden="true"></i></span>
                <span class="action" title="Добавить видео" data-view="video_field"><i class="fa fa-youtube"
                                                                                       aria-hidden="true"></i></span>
                <span class="action" title="Добавить описание" data-view="description_field"><i
                        class="fa fa-quote-right" aria-hidden="true"></i></span>
                <span class="action" title="Добавить подзаголовок" data-view="subtitle_field"><i class="fa fa-font"
                                                                                                 aria-hidden="true"></i></span>
                <span class="action" title="Добавить текст" data-view="text_field"><i class="fa fa-file-text"
                                                                                      aria-hidden="true"></i></span>
                </div>
            </fieldset>
        </div>
        <div class="field-input"></div>
        <div id="preview_container"></div>


        <?php if (!empty($_SESSION['publication'])): ?>

            <hr>
            <p class="clearfix">
                <button type="button" class="btn btn-default float-left multi-remove-elements">Удалить выбранные элементы</button>
                <button type="button" class="btn btn-danger cancel-publication float-right">Отмена</button>
                <a href="/public/preview" data-content="post" data-id="-1" class="session-config float-right">
                    <button type="button" class="btn btn-default preview-publication">Предпросмотр</button>
                </a>
                <button type="button" class="btn btn-primary submit-publication float-right">Опубликовать</button>
            </p>


        <?php endif; ?>
    </div>

<?php
session_start();
//$_SESSION['publication'] = array();
main_Model::pre($_SESSION['publication']);