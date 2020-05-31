<?php
/**
 * Created by PhpStorm.
 * User: Юрчик
 * Date: 15.05.2020
 * Time: 17:54
 * @var $html string
 */

$category = main_Controller::$categories_assoc[explode('/', $_GET['query'])[1]];

?>

<div class="category-container">
    <a href="/category"><?= $category ? $category : 'категория' ?><i class="fa fa-caret-down fa-lg" aria-hidden="true"></i></a><i class="fa fa-caret-down fa-sm" aria-hidden="true"></i>
    <ul>
        <?= $html ?>
    </ul>
</div>
