<?php
/**
 * @var $html string
 * Типовой шаблон
 */
?>

<fieldset>
    <legend>Менеджер меню</legend>
    <ul class="menu-list">
        <?= $html ?>
    </ul>
    <h5 class="pointer centered show-form">Добавление новых опций меню:</h5>
    <form action="" class="add-menu-option hidden">
        <div class="form-data">
            <input type="text" name="menu_option" class="form-control" placeholder="Название новой опции меню">
        </div>
        <div class="form-data">
            <input type="text" name="menu_option_url" class="form-control" placeholder="URL новой опции">
        </div>
        <hr>
        <input type="hidden" name="action" value="addMenuOption">
        <button type="submit" class="btn btn-primary">Добавить</button>
    </form>
</fieldset>

