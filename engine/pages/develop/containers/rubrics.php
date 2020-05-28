<?php
/**
 * @var $html string
 * Типовой шаблон
 */
?>

<fieldset>
    <legend>Менеджер категорий</legend>
    <ul class="category-list">
        <?= $html ?>
    </ul>
    <h5 class="pointer centered show-form">Добавление новых категорий:</h5>
    <form action="" class="add-rubric hidden">
        <div class="form-data">
            <input type="text" name="rubric_name" class="form-control" placeholder="Название новой рубрики">
        </div>
        <div class="form-data">
            <input type="text" name="rubric_url_name" class="form-control" placeholder="URL новой рубрики">
        </div>
        <hr>
        <input type="hidden" name="action" value="addCategory">
        <button type="submit" class="btn btn-primary">Добавить</button>
    </form>
</fieldset>

