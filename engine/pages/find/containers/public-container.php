<?php
/**
 * Created by PhpStorm.
 * User: Юрчик
 * Date: 09.03.2020
 * Time: 11:14
 */

?>
<div class="search container">
    <form action="" onsubmit="return false;" id="search-form">
        <div class="search-container">
            <input type="text" placeholder="Что будем искать?" class="form-control search-input">
            <div id="search-options"></div>
        </div>
        <button type="submit" class="btn btn-primary">Найти</button>
    </form>
</div>
<div class="container" id="publications-container">
    <?= $html ?>
</div>
