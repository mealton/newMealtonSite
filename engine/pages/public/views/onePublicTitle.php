<?php
/**
 * @var $title string
 * @var $author string
 * @var $username string
 * @var $views int
 */

session_start();
?>

<!--<div id="headerwrap">
    <div class="container">
        <div class="row centered">
            <div class="col-lg-8 col-lg-offset-2">
                <h1><?//= $long_title/?></h1>
            </div>
        </div>
        <!-- row -->
    </div>
    <!-- container -->
</div>-->
<div class="container">
    <br>
    <div class="row details">
        <div class="col">
            <!--<p>Данные по сессии:
                <?php /*//main_Model::pre($_GET['query'])*/?>
                <?php /*//main_Model::pre($_SESSION['config'])*/?>
            </p>-->
            <h1><?= $long_title ?> <?= main_Controller::mediaCounter($count_img, $count_video) ?></h1>
            <hr>
            <p>
                <span class="views"><strong>Просмотров</strong>: <?= $views ?></span>
                <span class="author"><strong>Автор</strong>: <a href="/authors/<?=$username?>/"><?= $username ?></a></span>
            </p>
        </div>
    </div>
</div>

