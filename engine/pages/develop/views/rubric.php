<?php
/**
 * Created by PhpStorm.
 * User: Юрчик
 * Date: 07.03.2020
 * Time: 16:38
 * @var $isActive string
 * @var $rubric_name string
 * @var $rubric_url_name string
 * @var $id int
 */
?>

<li class="item <?= $isActive === "deleted" ? 'deleted' : '' ?>">
    <div class="pointer category-title" onclick="Admin.showDetails(this)">
        <?= $rubric_name ?>
    </div>

    <div class="details hidden">
        <form action="">
            <div class="is-active">
                <label>Активен <input type="checkbox" value="<?= $id ?>"
                                      class="delete-category" <?= $isActive === "deleted" ? '' : 'checked' ?>></label>
            </div>
            <ul>
                <li><input type="text" name="rubric_name" class="form-control" placeholder="Название рубрики"
                           value="<?= $rubric_name ?>"></li>
                <li><input type="text" name="rubric_url_name" class="form-control" placeholder="URL рубрики"
                           value="<?= $rubric_url_name ?>"></li>
            </ul>
            <hr>
            <input type="hidden" name="id" value="<?= $id ?>">
            <button type="button" class="save-changes-category btn btn-primary">Сохранить изменения</button>
        </form>
    </div>
</li>
