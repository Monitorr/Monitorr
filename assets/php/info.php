<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <link type="text/css" href="../css/bootstrap.min.css" rel="stylesheet">
        <!-- <link type="text/css" href="../css/alpaca.min.css" rel="stylesheet"> -->
        <link type="text/css" href="../css/main.css" rel="stylesheet">

        <meta name="theme-color" content="#464646" />
        <meta name="theme_color" content="#464646" />
        
        <script type="text/javascript" src="../js/jquery.min.js"></script>
        <script type="text/javascript" src="../js/pace.js" async></script>
        <!-- <script type="text/javascript" src="../js/handlebars.js"></script> -->
        <!-- <script type="text/javascript" src="../js/alpaca.min.js"></script> -->
        <script type="text/javascript" src="../js/bootstrap.min.js"></script>

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

                #includedContent {
                    /* position: static; */
                    /* margin-top: -5rem; */
                    float: right;
                    width: 95% !important;
                }

            </style>

        <title>
            <?php 
                $str = file_get_contents('../data/user_preferences-data.json');
                $json = json_decode($str, true);
                $title = $json['sitetitle'];
                echo $title . PHP_EOL;
            ?>
            | Info
        </title>

        <?php include ('../config.php'); ?>
        <!-- <?php include ('../php/check.php') ;?> -->
        <?php include ('gitinfo.php'); ?>

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
            <div class="navbar-brand">
                Info
            </div>
        </div>


        <div>
            <br> <br>
            -	OS Version
            <br> <br>
            -	PHP Version
            <br> <br>
            -	Update check here??
             <br> <br>
            -	ChangeLog?
            <br> <br>
            -	Github link / Docker Hub link / WiKI / Feat request / Discord link
            <br> <br>
            -	Donate Link
             <br> <br>

        </div>

        <div id ="includedContent"> </div>


        <script>
            
                document.getElementById("includedContent").innerHTML='<object type="text/html" class="object" data="phpinfo.php" ></object>'
            
        </script>
        

        <!-- <div id="footer">

            <script src="../js/update.js" async></script>
            <script src="../js/update_auto.js" async></script>
        
            <p> <a class="footer a" href="https://github.com/monitorr/Monitorr" target="_blank"> Repo: Monitorr </a> | <a class="footer a" href="https://github.com/Monitorr/Monitorr/releases" target="_blank"> Version: <?php echo file_get_contents( "../js/version/version.txt" );?> </a> </p>

            <a class="footer a" id="version_check" style="cursor: pointer">Check for Update</a>
            
            <div id="version_check_auto"></div>
            
        </div> -->

</body>

</html>