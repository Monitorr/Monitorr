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

    <?php include ('assets/php/check.php') ;?>
    <?php include ('assets/php/gitinfo.php'); ?>
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
                $("#stats").load('assets/php/systembadges.php');
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
    <div>
    <?php
$myServices = array( 
    "monitorr1" => array( 
        "link" => "https://finflix.net/monitorr", 
        "image" => "monitorr.png"
        ), 
    "plex2" => array( 
        "link" => "https://plex.finflix.net", 
        "image" => "plex.png"
        ), 
    "sonarr" => array(  
        "link" => "https://finflix.net/sonarr", 
        "image" => "sonarr.png"
        ), 
    "radarr4" => array( 
        "link" => "https://finflix.net/radarr", 
        "image" => "radarr.png"
        ), 
   ); 

   foreach ( $myServices as $k => $v ) { 
       echo "<p>".$k."</p><p>".$v['link']."</p></p>".$v['image']."</p></p>";
   };
?>
        </div>
        <!-- /row -->
        <div class="row">
            <div class="col-md-12">
                <div class="row mt centered"> 

                    <div id="statusloop">            
                        <!-- loop data goes here -->
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!--<div id="stats" class="row mt centered" style="display:flex;justify-content:center;align-items:center;">
         system badges go here 
    </div>-->

    <!-- /container -->
    <div class="footer">
        <p><a href="https://github.com/monitorr/Monitorr/tree/develop"><?php echo $branch; ?></a>-<a href="<?php echo $commiturl; ?>"><?php echo $commit; ?></a></p>
    </div>
</body>

</html>
