<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="shortcut icon" type="image/x-icon" href="plexlanding.ico" />`
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="version" content="php">
    <style>
        body.offline #link-bar {
            display: none;
        }

        body.online #link-bar {
            display: block;
        }

        .auto-style1 {
            float: left;
            text-align: center;
        }
    </style>
    <?php include ('assets/php/check.php') ;?>
    <?php include ('assets/config.php'); ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script type="text/javascript">
     function statusCheck() {
       $("#statusloop").load('loop.php');
     }
     setInterval(statusCheck, 5000);
    </script>
    <title><?php echo $config['title']; ?></title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <!-- Custom styles -->
    <link href="assets/css/main.css" rel="stylesheet">
    <!-- Fonts from Google Fonts -->
    <link href='//fonts.googleapis.com/css?family=Lato:300,400,900' rel='stylesheet' type='text/css'>

</head>

<body onload="statusCheck()">

    <!-- Fixed navbar -->
    <br>
    <a class="navbar-brand" href="<?php echo $config['siteurl']; ?>" style="width: 100%">
        <div class="text-center">
            <h1><?php echo $config['title']; ?></h1>
        </div>
    </a>
    <br>
    <br>
    <div class="container">
        <div class="auto-style1">
            <a href="<?php echo $config['siteurl']; ?>">
            </a>
        </div>
        <!-- /row -->

        <div id="statusloop" class="row mt centered">
            <!-- loop data goes here -->
        </div>

    </div>
    <!-- /container -->

</body>

</html>
