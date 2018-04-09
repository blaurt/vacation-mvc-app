<?php
declare(strict_types=1);

use app\core\AppCore;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <title>Humanity Responsive Web Template</title>
    <!--

    Template 2081 Humanity

    http://www.tooplate.com/view/2081-Humanity

    -->
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <base href="<?= AppCore::$request->server['HTTP_HOST'] ?>">
    <!-- animate -->
    <link rel="stylesheet" href="assets/css/animate.min.css">
    <!-- bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- magnific pop up -->
    <link rel="stylesheet" href="assets/css/magnific-popup.css">
    <!-- font-awesome -->
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <!-- google font -->
    <link href='http://fonts.googleapis.com/css?family=Raleway:400,300,700,800' rel='stylesheet' type='text/css'>
    <!-- custom -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/mystyle.css">

</head>
<body data-spy="scroll" data-offset="50" data-target=".navbar-collapse">

<!-- start navigation -->
<div class="navbar navbar-default" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon icon-bar"></span>
                <span class="icon icon-bar"></span>
                <span class="icon icon-bar"></span>
            </button>
            <a href="/" class="navbar-brand"><img src="assets/images/logo.png" class="img-responsive" alt="logo"></a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="/" class="smoothScroll">HOME</a></li>

                <?php if (!AppCore::$user->isGuest()) : ?>
                    <li><a href="/?r=vacation/request" class="smoothScroll">Request vacation</a></li>
                    <li><a href="/?r=vacation/status" class="smoothScroll">Vacation status</a></li>
                    <?php if (AppCore::$user->hasPermission(\app\models\role\Role::MANAGE_VACATIONS)) : ?>
                        <li><a href="/?r=vacation/manage" class="smoothScroll">Manage vacations</a></li>
                    <?php endif; ?>
                    <li><a href="#" class="smoothScroll" id="logout-link">Logout</a></li>
                    <form name="logout_form" id="logout_form" method="post" action="/?r=user/logout">
                        <input type="hidden" name="perform_logout" value="1">
                    </form>
                <?php else: ?>
                    <li><a href="/?r=user/register" class="smoothScroll">Register</a></li>
                    <li><a href="/?r=user/login" class="smoothScroll">Login</a></li>
                <?php endif; ?>


            </ul>
        </div>
    </div>
</div>
<!-- end navigation -->

<!-- start home -->
<section id="home" class="text-center">

    <div class="container">
        <?= $content ?>
    </div>


    </div>
</section>
<!-- end home -->


<!-- jQuery -->
<script src="assets/js/jquery.js"></script>
<!-- bootstrap -->
<script src="assets/js/bootstrap.min.js"></script>
<!-- isotope -->
<script src="assets/js/isotope.js"></script>
<!-- images loaded -->
<script src="assets/js/imagesloaded.min.js"></script>
<!-- wow -->
<script src="assets/js/wow.min.js"></script>
<!-- smoothScroll -->
<script src="assets/js/smoothscroll.js"></script>
<!-- jquery flexslider -->
<script src="assets/js/jquery.flexslider.js"></script>
<!-- Magnific Pop up -->
<script src="assets/js/jquery.magnific-popup.min.js"></script>
<!-- custom -->
<script src="assets/js/custom.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>
