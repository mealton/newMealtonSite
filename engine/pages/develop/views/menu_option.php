<?php
/**
 * Created by PhpStorm.
 * User: Юрчик
 * Date: 07.03.2020
 * Time: 16:38
 * @var $isActive string
 * @var $menu_option string
 * @var $menu_option_url string
 * @var $id int
 */
?>

<li class="item <?=$isActive === "deleted" ? 'deleted' : ''?>">
    <div class="pointer menu-title" onclick="Admin.showDetails(this)">
        <?=$menu_option?>
    </div>
    <div class="details hidden">
        <form action="">
            <div class="is-active">
                <label>Активен <input type="checkbox" value="<?=$id?>" class="delete-menu-option" <?= $isActive === "deleted" ? '' : 'checked' ?>></label>
            </div>
            <ul>
                <li><input type="text" name="menu_option" class="form-control" placeholder="Название опции меню" value="<?=$menu_option?>"></li>
                <li><input type="text" name="menu_option_url" class="form-control" placeholder="URL опции" value="<?=$menu_option_url?>"></li>
            </ul>
            <hr>
            <input type="hidden" name="id" value="<?= $id ?>">
            <button type="button" class="save-changes-menu btn btn-primary">Сохранить изменения</button>
        </form>
    </div>
</li>
