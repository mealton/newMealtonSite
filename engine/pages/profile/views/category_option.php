<?php
/**
 * Created by PhpStorm.
 * User: Юрчик
 * Date: 08.03.2020
 * Time: 18:31
 * @var $rubric_name string
 * @var $id int
 */
session_start();
?>
<option value="<?=$id?>" <?=$id == $_SESSION['publication']['category'] ? 'selected' : ''?>><?=$rubric_name?></option>