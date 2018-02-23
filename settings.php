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
        <script type="text/javascript" src="assets/js/handlebars.js"></script>
        <script type="text/javascript" src="assets/js/alpaca.min.js"></script>
        <!-- <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script> -->

            <style>

                body {
                    height: 95%;
                    /* margin: 2vw !important; */
                    /* margin-top: 1vw !important; */
                    padding-left: 2rem;
                    padding-right: 1rem;
                    padding-bottom: 1rem;
                    /* overflow-y: scroll !important;  */
                    overflow-x: hidden !important;
                    /* color: white !important; */
                    background-color: #1F1F1F !important;
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
                float: left;
                /* width: 8rem; */
                margin: 0;
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
                    height: auto;
                    padding-top: 0;
                    background-color: #3d3d3d;
                    border-radius: 1rem;
                    box-shadow: 5px 5px 5px black;
                    -moz-transition: all 0.5s ease;
                    -o-transition: all 0.5s ease;
                    -webkit-transition: all 0.5s ease;
                    /* background: #1a1a1a; */

                    /* left: 220px; */
                    /* margin-top: 3rem; */
                    /* margin-left: -220px; */
                    overflow-x: hidden;
                    overflow-y: auto;
                    transition: all 0.5s ease;
                    width: 17rem;
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
                /* list-style: none; */
                /* margin: 0; */
                /* padding: 0; */
                /* position: absolute; */
                /* top: 0; */
                /* width: 220px; */
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
                /* padding: 10px 15px 10px 30px; */
                text-decoration: none;
                padding: 1rem;
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
                /* font-size: 20px; */
                /* height: 65px; */
                /* line-height: 44px; */
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

                #left {
                    /* padding-top: 5rem; */
                    padding-bottom: 1.5rem !important;
                }

                #footer {
                    position: fixed !important;
                    bottom: 0 !important;
                }

                a:link{

                    background-color: transparent !important;
                }

            </style>

        <title>
            <?php 
                $str = file_get_contents('assets/data/user_preferences-data.json');
                $json = json_decode($str, true);
                $title = $json['sitetitle'];
                echo $title . PHP_EOL;
            ?>
            | Settings
        </title>

        <?php include ('assets/config.php'); ?>
        <!-- <?php include ('assets/php/check.php') ;?> -->
        <?php include ('assets/php/gitinfo.php'); ?>



        <script>

            $(document).ready(function() {
                function update() {
                $.ajax({
                type: 'POST',
                url: 'assets/php/timestamp.php',
                timeout: 5000,
                success: function(data) {
                    $("#timer").html(data); 
                    window.setTimeout(update, 5000);
                    }
                });
                }
                update();
            });
            
        </script>
        
        <script>
        
            <?php $dt = new DateTime("now", new DateTimeZone($config['timezone'])); ?>   
    
            $servertimezone = "<?php echo $config['timezone']; ?>";
            
            $dt = "<?php echo $dt->format("D M d Y H:i:s"); ?>";

            var servertimezone = $servertimezone;

            var servertime = $dt;
                    
        </script>

        <script src="assets/js/clock.js" async></script>


<!--         <script type="text/javascript">

            var nIntervId;
            var onload;

            function statusCheck() {
                $("#statusloop").load('assets/php/loop.php');
                $("#stats").load('assets/php/systembadges.php');
            };

            $(document).ready(function () {
                $(":checkbox").change(function () {
                    if ($(this).is(':checked')) {
                        nIntervId = setInterval(statusCheck, <?php echo $config['rfsysinfo']; ?>);
                    } else {
                        clearInterval(nIntervId);
                    }
                });
                $('#buttonStart :checkbox').attr('checked', 'checked').change();
            });

        </script>  -->




    <!-- <script>
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
    </script> -->


        <script>
            $(function() {
                document.getElementById("includedContent").innerHTML='<object type="text/html" class="object" data="login.php" ></object>';
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


    <div id ="includedContent"> </div>

    <div id ="settingscolumn">

        <div id="left" class="Column">
            <div id="clock">
                <canvas id="canvas" width="120" height="120"></canvas>
                <div class="dtg" id="timer"></div>
            </div>
        </div>

        <div id="wrapper">
            <!-- <div class="overlay"></div> -->


        
            <!-- Sidebar -->
            <nav class="navbar navbar-inverse navbar-fixed-top" id="sidebar-wrapper" role="navigation">
                    
                    <div class="navbar-brand settingstitle">
                        Settings
                    </div>   
            
                <ul class="nav sidebar-nav">

                    <li>
                        <!-- <a href="assets/php/phpinfo.php" target="s"><i class="fa fa-fw fa-file-o"></i> Info </a> -->
                        <a href ="#" onclick="load_auth()"><i class="fa fa-fw fa-file-o"></i> Login </a> 
                    </li>

                    <li>
                        <!-- <a href="assets/php/phpinfo.php" target="s"><i class="fa fa-fw fa-file-o"></i> Info </a> -->
                        <a href ="#" onclick="load_info()"><i class="fa fa-fw fa-file-o"></i> Info </a> 
                    </li>
                    <li>
                        <!-- <a href="assets/php/monitorr-user_preferences.php" target="s"><i class="fa fa-fw fa-cog"></i> User Preferences </a> -->
                        <a href ="#" onclick="load_preferences()"><i class="fa fa-fw fa-cog"></i>  User Preferences </a> 
                    </li>
                    <li>
                        <!-- <a href="assets/php/monitorr-site_settings.php" target="s"><i class="fa fa-fw fa-cog"></i> Monitorr Settings </a> -->
                        <a href ="#" onclick="load_settings()"><i class="fa fa-fw fa-cog"></i>  Monitorr Settings </a> 
                    </li>
                    <li>
                        <!-- <a href="assets/php/monitorr-services_settings.php" target="#includedContent"><i class="fa fa-fw fa-cog"></i> Services Configuration </a> -->
                        <a href ="#" onclick="load_services()"><i class="fa fa-fw fa-cog"></i> Services Configuration  </a>
                    </li>
                    <li>
                        <a href="index.php"><i class="fa fa-fw fa-home"></i> Monitorr </a>
                    </li>

                </ul>
            </nav>
            <!-- /#sidebar-wrapper -->


        </div>
        <!-- /#wrapper -->


        <div id="version" class="Column">

            <!-- <script src="assets/js/update.js" async></script> -->
            <script src="assets/js/update_auto.js" async></script>
        
            <p> <a class="footer a" href="https://github.com/monitorr/Monitorr" target="_blank"> Repo: Monitorr </a> | <a class="footer a" href="https://github.com/Monitorr/Monitorr/releases" target="_blank"> <?php echo file_get_contents( "assets/js/version/version.txt" );?> </a> </p>
                        
            <div id="version_check_auto"></div>
            
        </div>

    </div>


        <script>
            function load_auth() {
                document.getElementById("includedContent").innerHTML='<object type="text/html" class="object" data="login.php" ></object>';
            }
        </script>

        <script>
            function load_info() {
                document.getElementById("includedContent").innerHTML='<object type="text/html" class="object" data="assets/php/info.php" ></object>';
            }
        </script>


        <script>
            function load_preferences() {
                document.getElementById("includedContent").innerHTML='<object type="text/html" class="object" data="assets/php/monitorr-user_preferences.php" ></object>';
            }
        </script>


        <script>
            function load_settings() {
                document.getElementById("includedContent").innerHTML='<object type="text/html" class="object" data="assets/php/monitorr-site_settings.php" ></object>';
            }
        </script>

        <script>
            function load_services() {
                document.getElementById("includedContent").innerHTML='<object type="text/html" class="object" data="assets/php/monitorr-services_settings.php" ></object>';
            }
        </script>




  <!-- <div id="includedContent"></div> -->


        <!-- <div id="footer">


            <script src="assets/js/update_auto.js" async></script>
        
            <p> <a class="footer a" href="https://github.com/monitorr/Monitorr" target="_blank"> Repo: Monitorr </a> | <a class="footer a" href="https://github.com/Monitorr/Monitorr/releases" target="_blank"> Version: <?php echo file_get_contents( "assets/js/version/version.txt" );?> </a> </p>
                        
            <div id="version_check_auto"></div>
            
        </div> -->

</body>

</html>