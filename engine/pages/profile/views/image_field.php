<?php
/**
 * Created by PhpStorm.
 * User: Юрчик
 * Date: 08.03.2020
 * Time: 19:49
 */
?>

<div class="form-data field-item">
    <hr>
    <fieldset>
        <legend>Добавить изображение</legend>
        <div class=" upload">
            <input type="text" name="profile_image" placeholder="url картинки" class="form-control upload-url">
            <button type="button" class="upload-url-btn btn btn-info" onclick="Upload.uploadUrlImage(this);">
                <i class="fa fa-upload" aria-hidden="true"></i>
                <img src="/assets/img/ajax-loader.gif">
            </button>
            <label class="upload-label">
                <input type="file" value="Загрузить с компьютера" multiple class="hidden file-upload-input"
                       accept="image/x-png, image/gif, image/jpeg" onchange="Upload.uploadFromComputer(this)">
                <span class="btn btn-primary">Загрузить с компьютера</span>
                <img src="/assets/img/ajax-loader.gif">
            </label>
        </div>
        <div class="preview"></div>
        <div class="left">
            <button type="button" class="btn btn-primary add-public-data" data-name="image">Добавить</button>
            <button type="button" class="btn btn-default" onclick="$(this).closest('.form-data').remove()">Отмена</button>
        </div>
    </fieldset>
</div>
