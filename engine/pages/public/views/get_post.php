<?php
/**
 * @var $title string
 * @var $url_name string
 * @var $image string
 * @var $description string
 * @var $username string
 * @var $author string
 * @var $date string
 * @var $import $import
 * @var $likes int
 * @var $views int
 */
?>

<!-- row -->

<br><br>
<hr>
<br><br>
<div class="row post">
    <div class="col-lg-6 centered">
        <a href="/<?= $_GET['page'] ?>/<?= $url_name ?>"><img src="<?= $image ?>" alt="<?= $description ?>"></a>
    </div>
    <!-- col-lg-6 -->
    <div class="col-lg-6">
        <h4><a href="/<?= $_GET['page'] ?>/<?= $url_name ?>><?= $title ?></a></h4>
        <div class="description">
            <p><?= $description ?>...</p>
        </div>
        <hr>
        <p>
            <i class="fa fa-user-circle-o"></i>&nbsp;&nbsp;<a href="/authors/<?= $username ?>"><?= $author ?></a><br/>
            <i class="fa fa-thumbs-o-up"></i>&nbsp;&nbsp;<?= $likes ?><br/>
            <i class="fa fa-eye"></i>&nbsp;&nbsp;<?= $views ?><br/>
            <i class="fa fa-calendar"></i>&nbsp;&nbsp;<?= $date ?><br/>
            <?php if($import):?>
            <i class="fa fa-link"></i>&nbsp;&nbsp;<a href="<?= $import ?>" target="_blank"><?= main_Model::getImportLink($import) ?></a>
            <?php endif;?>
        </p>
    </div>
</div>