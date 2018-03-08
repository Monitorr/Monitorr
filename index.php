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
        <link rel="manifest" href="webmanifest.json">
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
        <link rel="apple-touch-icon" href="favicon.ico">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Monitorr">
        <meta name="author" content="Monitorr">
        <meta name="version" content="php">
        <meta name="theme-color" content="#464646" />
        <meta name="theme_color" content="#464646" />


        <!-- Bootstrap core CSS -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet">

        <!-- Fonts from Google Fonts -->
        <link href='//fonts.googleapis.com/css?family=Lato:300,400,900' rel='stylesheet' type='text/css'>

        <!-- Custom styles -->
        <link href="assets/css/main.css" rel="stylesheet">
       

        <style>

            body {
                margin-top: 2rem;
                margin-bottom: 2vw;
                overflow-y: auto; 
                overflow-x: hidden;
                background-color: #1F1F1F;
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

        </style>


         <?php 


            $file = 'assets/config/datadir.json';

            if(!is_file($file)){

                $path = "assets/";

                include_once ('assets/config/monitorr-data-default.php');

                $title = $jsonusers['sitetitle'];

                $rftime = $jsonsite['rftime'];

                echo "default data path: " . $path;

                echo "<br>";

                echo "Datafile NOT present, Default title: " . $title;

                echo "<br>";

                echo "default data file: " . $jsonfileuser;

                echo "<br>";


            } 

            else {

                 
                $datafile = 'assets/config/datadir.json';

                include_once ('assets/config/monitorr-data.php');

                $title = $jsonusers['sitetitle'];

                echo "Datafile present, custon title: " . $title;

                    echo "<br>";
                
                echo "data dir: " . $datadir;

                    echo "<br>";

                echo "data file: " . $datafile . ': ' . filesize($datafile) . ' bytes';

                 echo "<br>";

            }

         ?> 


        <!-- <?php include ('assets/php/gitinfo.php'); ?> -->

        <title>
            <?php 
                echo $title . PHP_EOL;
            ?>
            | Monitorr
        </title>

        
        <script src="assets/js/jquery.min.js"></script>

        <script src="assets/js/pace.js" async></script>

        <script>

            $(document).ready(function() {
                function update() {

                    rftime =
                        <?php 
                            $rftime = $jsonsite['rftime'];
                            echo $rftime;
                        ?>

                    $.ajax({
                    type: 'POST',
                    url: 'assets/php/timestamp.php',
                    timeout: 5000,
                    success: function(data) {
                        $("#timer").html(data); 
                        window.setTimeout(update, rftime);
                        }
                    });
                }
                update();
            });
            
        </script>
        
        <script>

            $timezone = 
                "<?php 
                    $timezone = $jsonusers['timezone'];
                    echo $timezone;
                ?>";

            <?php $dt = new DateTime("now", new DateTimeZone("$timezone")); ?> ;
            
             $servertimezone = "<?php echo "$timezone"; ?>";

            $dt = "<?php echo $dt->format("D M d Y H:i:s"); ?>";

            var servertimezone = $servertimezone;

            var servertime = $dt;
                    
        </script>

        <script src="assets/js/clock.js" async></script>

        <script type="text/javascript">

            var nIntervId;
            var onload;

            function statusCheck() {
                $("#statusloop").load('assets/php/loop.php');
                $("#stats").load('assets/php/systembadges.php');
            };

            $(document).ready(function () {
                $(":checkbox").change(function () {

                    rfsysinfo =
                        <?php 
                            $rfsysinfo = $jsonsite['rfsysinfo'];
                            echo $rfsysinfo;
                        ?>

                    if ($(this).is(':checked')) {
                        nIntervId = setInterval(statusCheck, rfsysinfo);
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





        <div id="header">
            
            <div id="left" class="Column">
                <div id="clock">
                    <canvas id="canvas" width="120" height="120"></canvas>
                    <div class="dtg" id="timer"></div>
                </div>
            </div> 

            <div id="center">

                <div id="centertext">
                    <a class="navbar-brand" href="
                        <?php 
                            $siteurl = $jsonusers['siteurl'];
                            echo $siteurl . PHP_EOL;
                        ?>"> 
                        <?php
                            echo $title . PHP_EOL;
                        ?>
                    </a>
                </div>

                <div id="toggle">
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

            <div id="right" class="Column">

                <div id="stats" class="container centered">
                    <!-- system badges go here -->
                </div>

            </div> 

        </div>


              <!-- Check if datadir has been established: -->

        <?php 
        
            $file = 'assets/config/datadir.json';


            if(!is_file($file)){

                echo '<div id="datdirerror">';
                
                    echo 'Data directory not detected. Proceed to <a href="settings.php" target="s" title="Monitorr Settings"><i class="fa fa-fw fa-cog"></i> Monitorr Settings </a> and establish it.';
                
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

            <a href="settings.php" target="s" title="Monitorr Settings"><i class="fa fa-fw fa-cog"></i> Monitorr Settings </a>

            <script src="assets/js/update_auto.js" async></script>
        
            <p> <a class="footer a" href="https://github.com/monitorr/Monitorr" target="_blank" title="Monitorr Repo"> Monitorr </a> | <a class="footer a" href="https://github.com/Monitorr/Monitorr/releases" target="_blank" title="Monitorr Releases"> <?php echo file_get_contents( "assets/js/version/version.txt" );?> </a> </p>

            <div id="version_check_auto"></div>
            
        </div>

    </body>

</html>

