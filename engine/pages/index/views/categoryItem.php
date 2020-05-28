<?php
/**
 * Created by PhpStorm.
 * User: Юрчик
 * Date: 27.05.2020
 * Time: 17:54
 */
?>

<fieldset class="cat">
    <legend><a <?= $category_alias ? 'href="/category/' . $category_alias : '' ?>"><?= $category ?></a></legend>
    <ul><?= $publics ?></ul>
</fieldset>
<hr>
