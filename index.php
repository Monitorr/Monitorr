
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

        <?php $file = 'assets/config.php';
            //Use the function is_file to check if the config file already exists or not.
            if(!is_file($file)){
                copy('assets/config.php.sample', $file);
            } 
        ?>

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

        <?php include ('assets/config.php'); ?>
        <?php include ('assets/php/check.php') ;?>
        <?php include ('assets/php/gitinfo.php'); ?>

        <!-- <title><?php echo $config['title']; ?></title> -->

        <title>
            <?php 
                $str = file_get_contents('assets/data/user_preferences-data.json');
                $json = json_decode($str, true);
                $title = $json['sitetitle'];
                echo $title . PHP_EOL;
            ?>
            | Monitorr
        </title>
        
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
                        nIntervId = setInterval(statusCheck, <?php echo $config['rfsysinfo']; ?>);
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
                    <!-- <a class="navbar-brand" href="<?php echo $config['siteurl']; ?>"> <?php echo $config['title']; ?></a> -->
                    <a class="navbar-brand" href="
                        <?php 
                            $str = file_get_contents('assets/data/user_preferences-data.json');
                            $json = json_decode($str, true);
                            $siteurl = $json['siteurl'];
                            echo $siteurl . PHP_EOL;
                        ?>"> 
                        <?php
                            $str = file_get_contents('assets/data/user_preferences-data.json');
                            $json = json_decode($str, true);
                            $title = $json['sitetitle'];
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
            
        <div id="services" class="container">

            <div class="row">
                <div id="statusloop">            
                    <!-- loop data goes here -->
                </div>
            </div>

        </div>


<iframe height="500px" width="100%" src="assets/php/monitorr-services_settings.php" name="site_settings" style="border:none;"></iframe>



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

