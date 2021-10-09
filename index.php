<?php
session_start();
ob_start();
define("security", TRUE);
include_once("config/connect.php");
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Home</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/cart.css">
    <link rel="stylesheet" href="css/product.css">
    <link rel="stylesheet" href="css/success.css">
    <link rel="stylesheet" href="css/search.css">
    <link rel="stylesheet" href="css/category.css">
    <script src="js/jquery-3.3.1.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="admin/ckeditor/ckeditor.js"></script>
</head>

<body>

    <!--	Header	-->
    <div id="header">
        <div class="container">
            <div class="row">
                <!-- logo -->
                <?php
                include_once("modules/logo/logo.php")
                ?>
                <!-- search_box -->
                <?php
                include_once("modules/search/search_box.php")
                ?>
                <!-- cart_notify -->
                <?php
                include_once("modules/cart/cart_notify.php")
                ?>
            </div>
        </div>
        <!-- Toggler/collapsibe Button -->
        <button class="navbar-toggler navbar-light" type="button" data-toggle="collapse" data-target="#menu" >
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
    <!--	End Header	-->

    <!--	Body	-->
    <div id="body">
        <div class="container">
            <!-- menu -->
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <nav>
                        <!-- menu -->
                        <?php
                        include_once("modules/category/menu.php")
                        ?>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div id="main" class="col-lg-8 col-md-12 col-sm-12">
                    <!--	Slider	-->
                    <?php
                    include_once("modules/slider/slider.php")
                    ?>
                    <!--	End Slider	-->

                    <!-- Master Page Here -->
                    <?php
                    if (isset($_GET['page_layout'])) {
                        switch ($_GET['page_layout']) {
                            case 'cart': include_once('modules/cart/cart.php'); break;
                            case 'search': include_once('modules/search/search.php'); break;
                            case 'product': include_once('modules/product/product.php'); break;
                            case 'success': include_once('modules/cart/success.php'); break;
                            case 'category': include_once('modules/category/category.php'); break;
                        }
                    }else {
                        include_once("modules/product/product_featured.php");
                        include_once("modules/product/product_new.php");
                    }
                    ?>

                </div>

                <!-- sidebar -->
                <?php
                include_once("modules/sidebar/sidebar.php")
                ?>
            </div>
        </div>
    </div>
    <!--	End Body	-->

    <div id="footer-top">
        <div class="container">
            <div class="row">
                <!-- logo footer -->
                <?php
                include_once("modules/logo/logo_footer.php");
                include_once("modules/footer/address.php");
                include_once("modules/footer/service.php");
                include_once("modules/footer/hotline.php");
                ?>
            </div>
        </div>
    </div>

    <!--	Footer	-->
    <div id="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <p>
                        2018 © Vietpro Academy. All rights reserved. Developed by Vietpro Software.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!--	End Footer	-->
</body>

</html>