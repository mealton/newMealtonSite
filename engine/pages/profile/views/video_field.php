<?php
/**
 * Created by PhpStorm.
 * User: Юрчик
 * Date: 08.03.2020
 * Time: 19:52
 */
?>
<div class="form-data field-item">
    <hr>
    <fieldset>
        <legend>Добавить видео</legend>
        <div class="upload">
            <input type="text" name="video" placeholder="url видео" class="form-control">
            <button type="button" class="upload-url-btn btn btn-info" onclick="Profile.uploadVideo(this)">
                <i class="fa fa-upload" aria-hidden="true"></i>
                <img src="/assets/img/ajax-loader.gif">
            </button>
        </div>
        <button type="button" class="btn btn-primary add-public-data" data-name="video">Добавить</button>
        <button type="button" class="btn btn-default" onclick="$(this).closest('.form-data').remove()">Отмена</button>
    </fieldset>
    <br>
</div>
