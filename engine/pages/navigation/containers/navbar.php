<?php
/**
 * @var $html string
 */
session_start();
?>

<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">SP<i class="fa fa-circle"></i>T</a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <?= $html ?>
                <?= main_Controller::$profile_li?>
                <!--<li><a data-toggle="modal" data-target="#myModal" href="#myModal"><i class="fa fa-envelope-o"></i></a></li>-->
            </ul>
        </div>
        <!--/.nav-collapse -->
    </div>
</div>
