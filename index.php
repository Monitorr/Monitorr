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
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
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

    <?php include ('assets/config.php'); ?>
    <?php include ('assets/php/check.php') ;?>
    <?php include ('assets/php/gitinfo.php'); ?>
    
    <script src="assets/js/jquery.min.js"></script>

    <script src="assets/js/pace.js"></script>

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

        <script type="text/javascript">
            function statusCheck() {
                $("#statusloop").load('assets/php/loop.php');
                $("#stats").load('assets/php/systembadges.php');
                }
                setInterval(statusCheck, <?php echo $config['rfsysinfo']; ?>);
        </script>
        
    <title><?php echo $config['title']; ?></title>

</head>

<body onload="statusCheck()">
    <br>
    <!-- Fixed navbar -->
    <div class="navbar-brand">
        <a class="navbar-brand" href="<?php echo $config['siteurl']; ?>">
            <?php echo $config['title']; ?>
        </a>
    </div>

    <div class="container">
        <!-- /row -->
        <div class="row">
            <div class="col-md-12">
                <div class="row mt centered"> 
                    <div class="col-lg-6 col-lg-4 col-lg-3">
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

                        <script type="text/javascript" src="assets/js/clock.js"></script>

                    </div> 

                    <div id="statusloop">            
                        <!-- loop data goes here -->
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div id="stats" class="container centered">
            <!-- system badges go here -->
        </div>
    </div>

    <!-- /container -->

    <div class="footer">
      
       <p> <a href="https://github.com/monitorr/Monitorr" target="_blank"> Repo: Monitorr </a> // <a href="https://github.com/Monitorr/Monitorr/releases" target="_blank"> Version: <?php echo file_get_contents( "assets/js/version/version.txt" );?> </a> </p>

        <script src="assets/js/update.js" type="text/javascript"></script>
        
        <div>
            <a class="version_check" id="version_check" style="cursor: pointer;">Check for Update</a>
        </div>
    </div>
</body>

</html>

