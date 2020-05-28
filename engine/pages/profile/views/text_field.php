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
        <legend>Добавить текст</legend>
        <textarea name="text" placeholder="Текстовое поле" class="form-control"><?=$value?></textarea>
        <button type="button" class="btn btn-primary add-public-data" data-name="text">Добавить</button>
        <button type="button" class="btn btn-default" onclick="$(this).closest('.form-data').remove()">Отмена</button>
    </fieldset>
    <br>
</div>
