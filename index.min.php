<!DOCTYPE html>
<html lang="en">

    <!--
    __  __             _ _
    |  \/  |           (_) |
    | \  / | ___  _ __  _| |_ ___  _ __ _ __
    | |\/| |/ _ \| '_ \| | __/ _ \| '__| '__|
    | |  | | (_) | | | | | || (_) | |  | |
    |_|  |_|\___/|_| |_|_|\__\___/|_|  |_|
            made for the community
    by @seanvree, @wjbeckett, and @jonfinley
    https://github.com/Monitorr/Monitorr
    -->


    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
        <!-- <link rel="apple-touch-icon" href="favicon.ico"> -->

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Monitorr">
        <meta name="author" content="Monitorr">
        <meta name="version" content="php">
        <meta name="theme-color" content="#464646" />
        <meta name="theme_color" content="#464646" />

        <!-- Bootstrap core CSS -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom styles -->
        <link href="assets/css/main.css" rel="stylesheet">

        <style>

            body {
                margin-bottom: 1vw;
                overflow: scroll;
                overflow-x: hidden;
            }

            :root {
                font-size: 12px !important;
            }

            ::-webkit-scrollbar {
                width: 0px;  /* remove scrollbar space */
                background: transparent;  /* make scrollbar invisible */
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

            #left {
                width: 60% !important;
            }

            #time {
                margin-left: 2vw !important;
            }

            #center {
                position: absolute !important;
                 height: 5rem;
                width: 100% !important;
                max-width: 40rem;
                left: 51% !important;
                padding-top: 1rem;
                background-color: #1F1F1f;
                box-shadow: 0px 0px 0px 0px #1F1F1F, 0px 0px 0px 0px #1F1F1F, 10px 0px 10px 0px #1F1F1F, -10px 0px 10px 2px #1F1F1F;
            }

            #stats {
                display: flex !important;
                margin: 0 auto;
                float: none !important;
                box-sizing: content-box;
                height: 2em !important;
                width: 40em !important;

            }

            #uptime {
                    width: auto !important;
                    padding-bottom: .5rem !important;
            }

            #ping {

                    padding-top: .5rem !important;
            }

            #services {
                width: 100% !important;
            }

            #slider {
                padding-top: .25rem !important;
            }

            #textslider {
                padding-top: .50rem !important;
                padding-bottom: .50rem !important;
            }

        </style>


        <?php 
         
            $file = 'assets/config/datadir.json';

            if(!is_file($file)){

                $path = "assets/";

                include_once ('assets/config/monitorr-data-default.php');

                $title = $jsonusers['sitetitle'];

                $rftime = $jsonsite['rftime'];
                
            } 

            else {

                 
                $datafile = 'assets/config/datadir.json';

                include_once ('assets/config/monitorr-data.php');

                $title = $jsonusers['sitetitle'];

            }

         ?> 


        <title><?php $title = $jsonusers['sitetitle']; echo $title . PHP_EOL; ?></title>

        <script src="assets/js/jquery.min.js"></script>

        <script src="assets/js/pace.js" async></script>

        <script>
            $(document).ready(function() {
                function update() {
                $.ajax({
                type: 'POST',
                url: 'assets/php/timestamp.php',
                timeout: 5000,
                success: function(data) {
                    $("#timer").html(data);
                    window.setTimeout(update, 10000);
                    }
                });
                }
                update();
            });
        </script>

        <script type="text/javascript">

            var nIntervId;
            var onload;

            function statusCheck() {
                $("#statusloop").load('assets/php/loop.php');
                $("#stats").load('assets/php/systembadges.php');
            };

            $(document).ready(function () {
                $(":checkbox").change(function () {
                    if ($(this).is(':checked')) {
                        nIntervId = setInterval(statusCheck, <?php echo $jsonsite['rfsysinfo']; ?>);
                    } else {
                        clearInterval(nIntervId);
                    }
                });
                $('#buttonStart :checkbox').attr('checked', 'checked').change();
            });

        </script>

    </head>

    <body onload="statusCheck()">

        <script>
            document.body.className += ' fade-out';
            $(function() {
                $('body').removeClass('fade-out');
            });
        </script>


        <div id="headermin">

            <div id="left" class="Column">
                <div id="time">
                    <div class="dtg" id="timer"></div>
                </div>
            </div>

            <div id="center">
                <div id="stats" class="container centered">
                    <!-- system badges go here -->
                </div>
            </div>


            <div id="right" class="Column">

                <div id="togglemin">
                    <table id="slidertable">
                        <tr>
                            <th id="textslider">
                            Auto Refresh:
                            </th>
                            <th id="slider">
                                <label class="switch" id="buttonStart">
                                    <input type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </th>
                        </tr>
                    </table>
                </div>

            </div>

        </div>

            <!-- Check if datadir has been established: -->
        <?php

            $file = 'assets/config/datadir.json';

            if(!is_file($file)){

                echo '<div id="datdirerror">';
                    echo 'Data directory NOT detected';
                echo '</div>';

            }

            else {
            }

        ?>

        <div id="services" class="container">

            <div class="row">
                <div id="statusloop">
                    <!-- loop data goes here -->
                </div>
            </div>

        </div>

        <div id="footer">

            <p> <a class="footer a" href="https://github.com/monitorr/Monitorr" target="_blank"> Monitorr </a> | <a class="footer a" href="https://github.com/Monitorr/Monitorr/releases" target="_blank"> <?php echo file_get_contents( "assets/js/version/version.txt" );?> </a> </p>

        </div>

    </body>

</html>