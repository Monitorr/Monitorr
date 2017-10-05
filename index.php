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
  https://github.com/seanvree/Monitorr 
--> 

<head>
    <link rel="shortcut icon" type="image/x-icon" href="plexlanding.ico" />`
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="version" content="php">

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <!-- Custom styles -->
    <link href="assets/css/main.css" rel="stylesheet">
    <!-- Fonts from Google Fonts -->
    <link href='//fonts.googleapis.com/css?family=Lato:300,400,900' rel='stylesheet' type='text/css'>

    <style>
        body.offline #link-bar {
            display: none;
        }

        body.online #link-bar {
            display: block;
        }

        .auto-style1 {
            float: center;
            text-align: center;
        }
    </style>

    <?php include ('assets/php/check.php') ;?>
    <?php include ('assets/php/gitinfo.php'); ?>
    <?php include ('assets/config.php'); ?>
    <?php include ('assets/php/sysinfo.php'); ?>
    <?php include ('assets/php/meminfo.php'); ?>
    <?php include ('assets/php/uptime.php'); ?>


    <script src="assets/js/jquery.min.js"> </script>
        <script type= "text/javascript">
            $(document).ready(function() {
                function update() {
                $.ajax({
                type: 'POST',
                url: 'assets/php/timestamp.php',
                timeout: 5000,
                success: function(data) {
                    $("#timer").html(data); 
                  window.setTimeout(update, 2000);
                }
                });
                }
                update();
            });
        </script>

    <script src="assets/js/jquery.min.js"></script>
        <script type="text/javascript">
            function statusCheck() {
                $("#statusloop").load('assets/php/loop.php');
                }
                setInterval(statusCheck, 5000);
        </script>
        
    <title><?php echo $config['title']; ?></title>

</head>

<body onload="statusCheck()">

    <!-- Fixed navbar -->
    <div class="navbar-brand">
        <a class="navbar-brand" href="<?php echo $config['siteurl']; ?>">
            <?php echo $config['title']; ?>
        </a>
    </div>

    <div class="container">
        <!-- /row -->
        <div class="row mt centered"> 
            <div class="col-lg-4">
                <div class="clock">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" style="float:right;">
                        <g>
                            <circle r="55"/>
                            <g id="numbers"/>
                            <g id="ticks"/>
                            <g id="hands">
                                <g id="hour">
                                    <line x1="-2" y1="0" x2="15" y2="0"/>
                                </g>
                                <g id="minute">
                                    <line x1="-3" y1="0" x2="35" y2="0"/>
                                </g>
                                <g id="second">
                                    <line x1="-4" y1="0" x2="50" y2="0"/>
                                </g>
                            </g>
                        </g>
                    </svg>
                </div>

                <div class="dtg" id="timer"></div>

                <script src="assets/js/clock.js"></script>

            </div> 

            <div id="statusloop">            
                <!-- loop data goes here -->
            </div>
        </div>

    </div>

    <div id="systemstats" class="row mt centered">
        <div id="stats">
                <img class="shields" src="https://img.shields.io/badge/CPU-<?php echo round($system->getCpuLoadPercentage(), 2) ;?>%25-brightgreen.svg">
                <img class="shields" src="https://img.shields.io/badge/RAM-<?php echo round(getServerMemoryUsage(true), 2); ?>%25-brightgreen.svg">
                <img class="shields" src="https://img.shields.io/badge/uptime-<?php echo $total_uptime ;?>-blue.svg">
        </div>
    </div>

    <!-- /container -->
    <div class="footer">
        <p><a href="https://github.com/seanvree/Monitorr/tree/develop"><?php echo $branch; ?></a>-<a href="<?php echo $commiturl; ?>"><?php echo $commit; ?></a></p>
    </div>
</body>

</html>
