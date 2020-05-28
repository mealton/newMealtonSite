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
        <legend>Добавить описание</legend>
        <input type="text" name="description" placeholder="Описание видео или картинки" class="form-control">
        <button type="button" class="btn btn-primary add-public-data" data-name="description">Добавить</button>
        <button type="button" class="btn btn-default" onclick="$(this).closest('.form-data').remove()">Отмена</button>
    </fieldset>
</div>
