<?php
/**
 * Created by PhpStorm.
 * User: Юрчик
 * Date: 08.03.2020
 * Time: 14:45
 */
session_start();
?>

<div class="form-data">
    <input type="text" class="form-control" name="<?=$name?>" value="<?=$my_value?>" placeholder="<?=$placeholder?>">
    <input type="hidden" name="id" value="<?=$_SESSION['userId']?>">
    <button type="button" class="btn btn-primary update">Сохранить</button>
</div>