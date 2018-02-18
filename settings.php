<html lang="en">

    <head>
        <meta charset="utf-8">
        <link type="text/css" href="assets/css/bootstrap.min.css" rel="stylesheet" />
        <link type="text/css" href="assets/css/alpaca.min.css" rel="stylesheet" />
        <link type="text/css" href="assets/css/main.css" rel="stylesheet">

        <meta name="theme-color" content="#464646" />
        <meta name="theme_color" content="#464646" />
        
        <script type="text/javascript" src="assets/js/jquery.min.js"></script>
        <script type="text/javascript" src="assets/js/pace.js" async></script>
        <!-- <script type="text/javascript" src="../js/handlebars.js"></script> -->
        <!-- <script type="text/javascript" src="../js/alpaca.min.js"></script> -->
        <!-- <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script> -->

            <style>

                body {
                    margin: 2vw !important;
                    overflow-y: auto; 
                    overflow-x: hidden;
                    color: white !important;
                }

                legend { 
                    color: white;
                    }

                body::-webkit-scrollbar {
                    width: 10px;
                    background-color: #252525;
                }

                body::-webkit-scrollbar-track {
                    -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
                    box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
                    border-radius: 10px;
                    background-color: #252525;
                }

                body::-webkit-scrollbar-thumb {
                    border-radius: 10px;
                    -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, .3);
                    box-shadow: inset 0 0 6px rgba(0, 0, 0, .3);
                    background-color: #8E8B8B;
                } 

                body.offline #link-bar {
                    display: none;
                }

                body.online #link-bar {
                    display: block;
                }

                .auto-style1 {
                    text-align: center;
                }

                @import "https://designmodo.github.io/Flat-UI/dist/css/flat-ui.min.css";
                @import "https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css";
                @import "https://daneden.github.io/animate.css/animate.min.css";
                /*-------------------------------*/
                /*           VARIABLES           */
                /*-------------------------------*/
                body {
                position: relative;
                overflow-x: hidden;
                }
                body,
                html {
                height: 100%;
                }
                .nav .open > a {
                background-color: transparent;
                }
                .nav .open > a:hover {
                background-color: transparent;
                }
                .nav .open > a:focus {
                background-color: transparent;
                }
                /*-------------------------------*/
                /*           Wrappers            */
                /*-------------------------------*/
                #wrapper {
                -moz-transition: all 0.5s ease;
                -o-transition: all 0.5s ease;
                -webkit-transition: all 0.5s ease;
                padding-left: 0;
                transition: all 0.5s ease;
                }
                #wrapper.toggled {
                /* padding-left: 220px; */
                }
                #wrapper.toggled #sidebar-wrapper {
                width: 20%;
                }
                #wrapper.toggled #page-content-wrapper {
                margin-right: -220px;
                position: absolute;
                }
                #sidebar-wrapper {
                -moz-transition: all 0.5s ease;
                -o-transition: all 0.5s ease;
                -webkit-transition: all 0.5s ease;
                background: #1a1a1a;
                height: 100%;
                left: 220px;
                margin-left: -220px;
                overflow-x: hidden;
                overflow-y: auto;
                transition: all 0.5s ease;
                width: 0;
                z-index: 1000;
                }
                #sidebar-wrapper::-webkit-scrollbar {
                display: none;
                }
                #page-content-wrapper {
                padding-top: 70px;
                width: 100%;
                }
                /*-------------------------------*/
                /*     Sidebar nav styles        */
                /*-------------------------------*/
                .sidebar-nav {
                list-style: none;
                margin: 0;
                padding: 0;
                position: absolute;
                top: 0;
                width: 220px;
                }
                .sidebar-nav li {
                display: inline-block;
                line-height: 20px;
                position: relative;
                width: 100%;
                }
                .sidebar-nav li:before {
                background-color: #1c1c1c;
                content: '';
                height: 100%;
                left: 0;
                position: absolute;
                top: 0;
                -webkit-transition: width 0.2s ease-in;
                transition: width 0.2s ease-in;
                width: 3px;
                z-index: -1;
                }
                .sidebar-nav li:first-child a {
                background-color: #1a1a1a;
                color: #ffffff;
                }
                .sidebar-nav li:nth-child(2):before {
                background-color: #402d5c;
                }
                .sidebar-nav li:nth-child(3):before {
                background-color: #4c366d;
                }
                .sidebar-nav li:nth-child(4):before {
                background-color: #583e7e;
                }
                .sidebar-nav li:nth-child(5):before {
                background-color: #64468f;
                }
                .sidebar-nav li:nth-child(6):before {
                background-color: #704fa0;
                }
                .sidebar-nav li:nth-child(7):before {
                background-color: #7c5aae;
                }
                .sidebar-nav li:nth-child(8):before {
                background-color: #8a6cb6;
                }
                .sidebar-nav li:nth-child(9):before {
                background-color: #987dbf;
                }
                .sidebar-nav li:hover:before {
                -webkit-transition: width 0.2s ease-in;
                transition: width 0.2s ease-in;
                width: 100%;
                }
                .sidebar-nav li a {
                color: #dddddd;
                display: block;
                padding: 10px 15px 10px 30px;
                text-decoration: none;
                }
                .sidebar-nav li.open:hover before {
                -webkit-transition: width 0.2s ease-in;
                transition: width 0.2s ease-in;
                width: 100%;
                }
                .sidebar-nav .dropdown-menu {
                background-color: #222222;
                border-radius: 0;
                border: none;
                -webkit-box-shadow: none;
                        box-shadow: none;
                margin: 0;
                padding: 0;
                position: relative;
                width: 100%;
                }
                .sidebar-nav li a:hover,
                .sidebar-nav li a:active,
                .sidebar-nav li a:focus,
                .sidebar-nav li.open a:hover,
                .sidebar-nav li.open a:active,
                .sidebar-nav li.open a:focus {
                background-color: transparent;
                color: #ffffff;
                text-decoration: none;
                }
                .sidebar-nav > .sidebar-brand {
                font-size: 20px;
                height: 65px;
                line-height: 44px;
                }
                /*-------------------------------*/
                /*       Hamburger-Cross         */
                /*-------------------------------*/
                .hamburger {
                background: transparent;
                border: none;
                display: block;
                height: 32px;
                margin-left: 15px;
                position: fixed;
                top: 20px;
                width: 32px;
                z-index: 999;
                }
                .hamburger:hover {
                outline: none;
                }
                .hamburger:focus {
                outline: none;
                }
                .hamburger:active {
                outline: none;
                }
                .hamburger.is-closed:before {
                -webkit-transform: translate3d(0, 0, 0);
                -webkit-transition: all 0.35s ease-in-out;
                color: #ffffff;
                content: '';
                display: block;
                font-size: 14px;
                line-height: 32px;
                opacity: 0;
                text-align: center;
                width: 100px;
                }
                .hamburger.is-closed:hover before {
                -webkit-transform: translate3d(-100px, 0, 0);
                -webkit-transition: all 0.35s ease-in-out;
                display: block;
                opacity: 1;
                }
                .hamburger.is-closed:hover .hamb-top {
                -webkit-transition: all 0.35s ease-in-out;
                top: 0;
                }
                .hamburger.is-closed:hover .hamb-bottom {
                -webkit-transition: all 0.35s ease-in-out;
                bottom: 0;
                }
                .hamburger.is-closed .hamb-top {
                -webkit-transition: all 0.35s ease-in-out;
                background-color: rgba(255, 255, 255, 0.7);
                top: 5px;
                }
                .hamburger.is-closed .hamb-middle {
                background-color: rgba(255, 255, 255, 0.7);
                margin-top: -2px;
                top: 50%;
                }
                .hamburger.is-closed .hamb-bottom {
                -webkit-transition: all 0.35s ease-in-out;
                background-color: rgba(255, 255, 255, 0.7);
                bottom: 5px;
                }
                .hamburger.is-closed .hamb-top,
                .hamburger.is-closed .hamb-middle,
                .hamburger.is-closed .hamb-bottom,
                .hamburger.is-open .hamb-top,
                .hamburger.is-open .hamb-middle,
                .hamburger.is-open .hamb-bottom {
                height: 4px;
                left: 0;
                position: absolute;
                width: 100%;
                }
                .hamburger.is-open .hamb-top {
                -webkit-transform: rotate(45deg);
                -webkit-transition: -webkit-transform 0.2s cubic-bezier(0.73, 1, 0.28, 0.08);
                background-color: #fff;
                margin-top: -2px;
                top: 50%;
                }
                .hamburger.is-open .hamb-middle {
                background-color: #fff;
                display: none;
                }
                .hamburger.is-open .hamb-bottom {
                -webkit-transform: rotate(-45deg);
                -webkit-transition: -webkit-transform 0.2s cubic-bezier(0.73, 1, 0.28, 0.08);
                background-color: #fff;
                margin-top: -2px;
                top: 50%;
                }
                .hamburger.is-open:before {
                -webkit-transform: translate3d(0, 0, 0);
                -webkit-transition: all 0.35s ease-in-out;
                color: #ffffff;
                content: '';
                display: block;
                font-size: 14px;
                line-height: 32px;
                opacity: 0;
                text-align: center;
                width: 100px;
                }
                .hamburger.is-open:hover before {
                -webkit-transform: translate3d(-100px, 0, 0);
                -webkit-transition: all 0.35s ease-in-out;
                display: block;
                opacity: 1;
                }
                /*-------------------------------*/
                /*          Dark Overlay         */
                /*-------------------------------*/
                .overlay {
                position: fixed;
                display: none;
                width: 100%;
                height: 100%;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.4);
                z-index: 1;
                }
                /* SOME DEMO STYLES - NOT REQUIRED */
/*                 body,
                html {
                background-color: #583e7e;
                }
                body h1,
                body h2,
                body h3,
                body h4 {
                color: rgba(255, 255, 255, 0.9);
                }
                body p,
                body blockquote {
                color: rgba(255, 255, 255, 0.7);
                }
                body a {
                color: rgba(255, 255, 255, 0.8);
                text-decoration: underline;
                }
                body a:hover {
                color: #fff;
                } */


            </style>

        <title>
            <?php 
                $str = file_get_contents('assets/data/site_settings-data.json');
                $json = json_decode($str, true);
                $title = $json['sitetitle'];
                echo $title . PHP_EOL;
            ?>
            | Site Settings
        </title>

        <?php include ('assets/config.php'); ?>
        <!-- <?php include ('assets/php/check.php') ;?> -->
        <?php include ('assets/php/gitinfo.php'); ?>

    <script>
        $(document).ready(function () {
            var trigger = $('.hamburger'),
                overlay = $('.overlay'),
                isClosed = false;

                trigger.click(function () {
                hamburger_cross();      
                });

                function hamburger_cross() {

                if (isClosed == true) {          
                    overlay.hide();
                    trigger.removeClass('is-open');
                    trigger.addClass('is-closed');
                    isClosed = false;
                } else {   
                    overlay.show();
                    trigger.removeClass('is-closed');
                    trigger.addClass('is-open');
                    isClosed = true;
                }
            }
            
            $('[data-toggle="offcanvas"]').click(function () {
                    $('#wrapper').toggleClass('toggled');
            });  
        });
    </script>

    </head>

<body>

        <script>
            document.body.className += ' fade-out';
            $(function() { 
                $('body').removeClass('fade-out'); 
            });
        </script>


        <div id="centertext">
            <!-- <a class="navbar-brand" href="<?php echo $config['siteurl']; ?>"> <?php echo $config['title']; ?></a> -->
            <a class="navbar-brand" href="
                <?php 
                    $str = file_get_contents('assets/data/site_settings-data.json');
                    $json = json_decode($str, true);
                    $siteurl = $json['siteurl'];
                    echo $siteurl . PHP_EOL;
                ?>"> 
                <?php
                    $str = file_get_contents('assets/data/site_settings-data.json');
                    $json = json_decode($str, true);
                    $title = $json['sitetitle'];
                    echo $title . PHP_EOL;
                ?>
            </a>
        </div>


<!-- <iframe height="100%" width="75%" src="" name="info" style="border:none; float:right;"></iframe> -->
<iframe height="100%" width="75%" src="" name="site_settings" style="border:none; float:right;"></iframe>


    <div id="wrapper">
        <div class="overlay"></div>
    
        <!-- Sidebar -->
        <nav class="navbar navbar-inverse navbar-fixed-top" id="sidebar-wrapper" role="navigation">
            <ul class="nav sidebar-nav">
                <li class="sidebar-brand">
                    <a href="#">
                        <div class="navbar-brand">
                            Settings
                        </div>
                    </a>
                </li>
                <li>
                    <a href="assets/php/phpinfo.php" target="info"><i class="fa fa-fw fa-file-o"></i> Info </a>
                </li>
                <li>
                    <a href="assets/php/monitorr-site_settings.php" target="site_settings"><i class="fa fa-fw fa-cog"></i> site settings</a>
                </li>
                <li>
                    <a href="#"><i class="fa fa-fw fa-cog"></i> GUI </a>
                </li>
                <li>
                    <a href="#"><i class="fa fa-fw fa-cog"></i> services</a>
                </li>
                <li>
                    <a href="index.php"><i class="fa fa-fw fa-home"></i> Monitorr </a>
                </li>
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-fw fa-plus"></i> Dropdown <span class="caret"></span></a>
                  <ul class="dropdown-menu" role="menu">
                    <li class="dropdown-header">Dropdown heading</li>
                    <li><a href="#">Action</a></li>
                  </ul>
                </li>
            </ul>
        </nav>
        <!-- /#sidebar-wrapper -->



        <!-- Page Content -->
        <div id="page-content-wrapper">
          <button type="button" class="hamburger is-closed animated fadeInLeft" data-toggle="offcanvas">
            <span class="hamb-top"></span>
            <span class="hamb-middle"></span>
            <span class="hamb-bottom"></span>
          </button>
            <div class="container">
                <div class="row">

                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->



        <div id="footer">

            <script src="assets/js/update.js" async></script>
            <script src="assets/js/update_auto.js" async></script>
        
            <p> <a class="footer a" href="https://github.com/monitorr/Monitorr" target="_blank"> Repo: Monitorr </a> | <a class="footer a" href="https://github.com/Monitorr/Monitorr/releases" target="_blank"> Version: <?php echo file_get_contents( "assets/js/version/version.txt" );?> </a> </p>

            <!-- <a class="footer a" id="version_check" style="cursor: pointer">Check for Update</a> -->
            
                <br>
            
            <div id="version_check_auto"></div>
            
        </div>

</body>

</html>