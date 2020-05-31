<?php
/**
 * @var $title string
 * @var $author string
 * @var $username string
 * @var $views int
 */

session_start();
?>

<div class="container">
    <br>
    <div class="row details">
        <div class="col">
            <!--<p>Данные по сессии:
                <?php /*//main_Model::pre($_GET['query'])*/?>
                <?php /*//main_Model::pre($_SESSION['config'])*/?>
            </p>-->
            <div class="neighbors-publics"><?=$prev_next_html?></div>
            <hr>
            <h1><?= $long_title ?> <?= main_Controller::mediaCounter($count_img, $count_video) ?></h1>
            <hr>
            <p>
                <span class="views"><strong>Просмотров</strong>: <?= $views ?></span>
                <span class="author"><strong>Автор</strong>: <a href="/authors/<?=$username?>/"><?= $username ?></a></span>
            </p>
        </div>
    </div>
</div>

