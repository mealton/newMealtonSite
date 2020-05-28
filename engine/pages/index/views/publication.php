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

<div class="row post" id="post-<?= $public_id ?>">
    <br><br>
    <hr>
    <br><br>
    <div class="col-lg-6 centered">
        <a href="/public/<?= $public_id ?>::<?= $alias ?>" data-content="post" data-id="<?= $public_id ?>"
           class="session-config">
            <img class="img-responsive" src="<?= $image_default ? str_replace('preview', 'fullsize', $image_default) : str_replace('preview', 'fullsize', $random_img) ?>"
                 alt="<?= $description ?>">
        </a>
    </div>
    <!-- col-lg-6 -->
    <div class="col-lg-6">
        <h4><a href="/public/<?= $public_id ?>::<?= $alias ?>" data-content="post" data-id="<?= $public_id ?>"
               class="session-config">
                <?= $long_title ? $long_title : $short_title ?> <?= main_Controller::mediaCounter($count_img, $count_video) ?></a>
        </h4>
        <div class="description">
            <p><?= mb_substr($description, 0, 300) ?>...</p>
        </div>
        <hr>
        <p>
            <i class="fa fa-newspaper-o" aria-hidden="true"></i>&nbsp;&nbsp;<a
                href="/category/<?= $rubric_url_name ?>"><?= $rubric_name ?></a><br/>
            <!--<i class="fa fa-user-circle-o"></i>-->
            <span class="profile-image" style="background-image: url('<?= $profile_image ?>');"></span>
            &nbsp;&nbsp;<a href="/authors/<?= $username ?>"><?= $username ?></a><br/>
            <i class="fa fa-thumbs-o-up"></i>&nbsp;&nbsp;<?= $likes ?><br/>
            <i class="fa fa-eye"></i>&nbsp;&nbsp;<?= $views ?><br/>
            <i class="fa fa-calendar"></i>&nbsp;&nbsp;<?= main_Controller::dateRusFormat($created_on) ?><br/>
            <?php if ($imported): ?>
                <i class="fa fa-link"></i>&nbsp;&nbsp;<a href="<?= $imported ?>"
                                                         target="_blank"><?= main_Model::getImportLink($imported) ?></a>
                <br/>
            <?php endif; ?>

            <?php if ($hashtags): ?>
                <i class="fa fa-tags"></i>&nbsp;&nbsp;<?= main_Model::getTagsString($hashtags) ?>
            <?php endif; ?>
        </p>
    </div>
</div>