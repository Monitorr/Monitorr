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

    <?php include ('assets/config.php'); ?>

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
    <a class="navbar-brand" href="<?php echo $config['siteurl']; ?>" style="width: 100%">
        <div class="text-center">
            <h1><?php echo $config['title']; ?></h1>
        </div>
    </a>

    <div class="container">
        <div class="auto-style1">
            <a href="<?php echo $config['siteurl']; ?>">
            </a>
        </div>

        <!-- /row -->
        <div class="row mt centered"> 
            <div class="col-lg-4">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" style="float:right;">
                    <g>
                        <circle r="80"/>
                        <g id="numbers"/>
                        <g id="ticks"/>
                        <g id="hands">
                            <g id="hour">
                                <line x1="-2" y1="0" x2="30" y2="0"/>
                            </g>
                            <g id="minute">
                                <line x1="-3" y1="0" x2="55" y2="0"/>
                            </g>
                            <g id="second">
                                <line x1="-4" y1="0" x2="75" y2="0"/>
                            </g>
                        </g>
                    </g>
                </svg>
                <h4><strong>Server local DTG:</strong></h4>
                <div class="dtg" id="timer"></div>
            </div> 

            <script  src="assets/js/clock.js"></script>
        
            <div id="statusloop">            
                <!-- loop data goes here -->
            </div>
            
        </div>

    </div>
    <!-- /container -->

</body>

</html>
