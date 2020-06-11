<?php

/**
 * @var $title string
 * @var $description string
 * @var $label string
 * @var $content string
 */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?=$title?></title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="keywords" content="Публикации, юмор, приколы, ретро, посты, Домашняя страничка Юрий Титова, Мелтон, Mealton, mealton">
    <meta name="description" content="<?=$description?>">

    <!--yandex web master-->
    <meta name="yandex-verification" content="575fe42069bd1ed9" />

    <!--PLYR Video Player-->
    <script src="/assets/vendors/plyr/plyr.js"></script>
    <link rel="stylesheet" href="/assets/vendors/plyr/plyr.css"/>

    <!-- Favicons -->
    <link href="/assets/img/favicon.png" rel="icon">
    <link href="/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900|Raleway:400,300,700,900" rel="stylesheet">

    <!-- Bootstrap CSS File -->
    <link href="/assets/vendors/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- jquery-ui -->
    <link href="/assets/vendors/jquery/jquery-ui.min.css" rel="stylesheet">

    <!-- Libraries CSS Files -->
    <link href="/assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <!-- Main Stylesheet File -->
    <link href="/assets/vendors/css/style.css" rel="stylesheet">


    <!-- Mobile Stylesheet File -->
    <link href="/assets/vendors/css/mobile.css" rel="stylesheet">

    <meta property="og:image" content="<?=$label?>">

    <!--Own extra styles-->
    <?= main_Controller::$css ?>

    <!-- =======================================================
      Template Name: Spot
      Template URL: https://templatemag.com/spot-bootstrap-freelance-template/
      Author: TemplateMag.com
      License: https://templatemag.com/license/
    ======================================================= -->
</head>

<body>
<div data-image='<?=$label?>'></div>

<!-- Fixed navbar -->
<?= main_Controller::$navigation ?>

<main class="main-container">
    <!--Content-->
    <div class="main-content">
        <?= $content ?>
    </div>
    <!--Sidebar-->
    <?= main_Controller::$sidebar ?>
</main>

<!-- FOOTER -->
<div id="f">
    <div class="container">
        <div class="row centered">
            <a href="#"><i class="fa fa-twitter"></i></a><a href="#"><i class="fa fa-facebook"></i></a><a href="#"><i
                    class="fa fa-dribbble"></i></a>
        </div>
        <!-- row -->
    </div>
    <!-- container -->
</div>
<!-- Footer -->

<!-- MODAL FOR CONTACT -->
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">contact us</h4>
            </div>
            <div class="modal-body">
                <div class="row centered">
                    <p>We are available 24/7, so don't hesitate to contact us.</p>
                    <p>
                        Somestreet Ave, 987<br/> London, UK.<br/> +44 8948-4343<br/> contact@example.com
                    </p>

                    <form class="contact-form php-mail-form" role="form" action="contactform/contactform.php"
                          method="POST">

                        <div class="form-group">
                            <label for="contact-name">Your Name</label>
                            <input type="name" name="name" class="form-control" id="contact-name"
                                   placeholder="Your Name" data-rule="minlen:4"
                                   data-msg="Please enter at least 4 chars">
                            <div class="validate"></div>
                        </div>
                        <div class="form-group">
                            <label for="contact-email">Your Email</label>
                            <input type="email" name="email" class="form-control" id="contact-email"
                                   placeholder="Your Email" data-rule="email" data-msg="Please enter a valid email">
                            <div class="validate"></div>
                        </div>
                        <div class="form-group">
                            <label for="contact-subject">Subject</label>
                            <input type="text" name="subject" class="form-control" id="contact-subject"
                                   placeholder="Subject" data-rule="minlen:4"
                                   data-msg="Please enter at least 8 chars of subject">
                            <div class="validate"></div>
                        </div>

                        <div class="form-group">
                            <label for="contact-message">Your Message</label>
                            <textarea class="form-control" name="message" id="contact-message"
                                      placeholder="Your Message" rows="5" data-rule="required"
                                      data-msg="Please write something for us"></textarea>
                            <div class="validate"></div>
                        </div>

                        <div class="loading"></div>
                        <div class="error-message"></div>
                        <div class="sent-message">Your message has been sent. Thank you!</div>

                        <div class="form-send">
                            <button type="submit" class="btn btn-large">Send Message</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!--Modal for sign_in-->
<div class="modal fade" id="sign_in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Форма авторизации</h4>
            </div>
            <div class="modal-body">
                <div class="row">

                    <form class="sing-in-form">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Email address</label>
                            <input type="text" class="form-control" name="username" placeholder="Имя пользователя" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Пароль" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<!-- /.modal -->

<div id="copyrights">
    <div class="container">
        <p>
            &copy; Copyrights <strong>Spot</strong>. All Rights Reserved
        </p>
        <div class="credits">
            <!--
              You are NOT allowed to delete the credit link to TemplateMag with free version.
              You can delete the credit link only if you bought the pro version.
              Buy the pro version with working PHP/AJAX contact form: https://templatemag.com/spot-bootstrap-freelance-template/
              Licensing information: https://templatemag.com/license/
            -->
            Created with Spot template by <a href="https://templatemag.com/">TemplateMag</a>
        </div>
    </div>
</div>

<!--Lift up-->
<div class="lift"></div>

<!-- JavaScript Libraries -->
<script src="/assets/vendors/jquery/jquery.min.js"></script>
<script src="/assets/vendors/jquery/jquery-ui.min.js"></script>
<script src="/assets/vendors/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/vendors/php-mail-form/validate.js"></script>
<script src="/assets/vendors/chart/chart.js"></script>

<!-- Template Main Javascript File -->
<script src="/assets/vendors/js/main.js"></script>

<!--Own extra js scripts-->
<?= main_Controller::$js ?>

</body>
</html>
