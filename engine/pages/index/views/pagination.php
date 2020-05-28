<?php
/**
 * Created by PhpStorm.
 * User: Юрчик
 * Date: 28.05.2020
 * Time: 6:12
 */

?>
<a <?= $page != $current ? "href='/$content/$page'" : ""?> >
    <button class="pagination-item btn <?= $page == $current ? 'btn-primary' : 'btn-default' ?>">
        <?= $page ?>
    </button>
</a>