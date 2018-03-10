<!-- <!DOCTYPE html> -->
<html lang="en">

    <!--
         Monitorr settings page
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
                margin: auto;
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


        <?php 

            $datafile = $datadir . 'users.db';
            
            $db_sqlite_path = $datafile;

        ?>

        <title>
            <?php 
                echo $title . PHP_EOL;
            ?>
            | Settings
        </title>


        <?php include ('assets/php/gitinfo.php'); ?>

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


         <script>
            $(function() {
                document.getElementById("includedContent").innerHTML='<object type="text/html" class="object" data="assets/php/monitorr-info.php" ></object>';
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


    <div id ="settingscolumn" class="settingscolumn">

        <div id="left" class="Column">
            <div id="clock">
                <canvas id="canvas" width="120" height="120"></canvas>
                <div class="dtg" id="timer"></div>
            </div>
        </div>

        <div id="wrapper">

        
            <!-- Sidebar -->
            <nav class="navbar navbar-inverse navbar-fixed-top" id="sidebar-wrapper" role="navigation">
                    
                    <div class="navbar-brand settingstitle">
                        Settings
                    </div>   
            
                <ul class="nav sidebar-nav">

                    <li>
                        <a href ="#" onclick="load_info()"><i class="fa fa-fw fa-info"></i> Info </a> 
                    </li>
                    <li>
                        <a href ="#" onclick="load_preferences()"><i class="fa fa-fw fa-cog"></i>  User Preferences </a> 
                    </li>
                    <li>
                        <a href ="#" onclick="load_settings()"><i class="fa fa-fw fa-cog"></i>  Monitorr Settings </a> 
                    </li>
                    <li>
                        <a href ="#" onclick="load_services()"><i class="fa fa-fw fa-cog"></i> Services Configuration  </a>
                    </li>

                    <li>
                       <a href="assets/php/monitorr-info.php?action=logout"><i class="fa fa-fw fa-sign-out"></i> Log-out </a>
                    </li>
                    <li>
                        <a href="index.php"><i class="fa fa-fw fa-home"></i> Monitorr </a>
                    </li>

                </ul>
            </nav>

        </div>


        <div id="version" >

            <script src="assets/js/update_auto.js" async></script>
        
            <p> <a class="footer a" href="https://github.com/monitorr/Monitorr" target="_blank" title="Monitorr Repo"> Monitorr </a> | <a class="footer a" href="https://github.com/Monitorr/Monitorr/releases" target="_blank" title="Monitorr Releases"> <?php echo file_get_contents( "assets/js/version/version.txt" );?> </a> </p>
                        
            <div id="version_check_auto"></div>

            
             <div id="reginfo" >

                <?php 

                    if (!is_dir($datadir)) {
                        echo "Data directory NOT present.";
                    }

                    else {
                        echo 'Data directory present:';
                            echo "<br>";
                        echo $datadir;
                    }

                        echo "<br>";

                    if (!is_file($datafile)) {
                        echo "Database file NOT present.";
                        echo "<br><br>";
                    }

                    else {
                        echo 'Database file present:';
                            echo "<br>";
                        echo $datafile;
                            echo "<br><br>";
                    }

                ?>

            </div>
            
        </div>

    </div>

        <div id ="includedContent"> </div>

        <script>
            function load_info() {
                document.getElementById("includedContent").innerHTML='<object type="text/html" class="object" data="assets/php/monitorr-info.php" ></object>';
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


</body>

</html>