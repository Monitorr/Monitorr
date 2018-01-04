
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
        <link rel="apple-touch-icon" href="favicon.ico">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Monitorr">
        <meta name="author" content="Monitorr">
        <meta name="version" content="php">

        <!-- Bootstrap core CSS -->
        <link href="assets/css/bootstrap.css" rel="stylesheet">

        <!-- Fonts from Google Fonts -->
        <link href='//fonts.googleapis.com/css?family=Lato:300,400,900' rel='stylesheet' type='text/css'>

        <!-- Custom styles -->
        <link href="assets/css/main.css" rel="stylesheet">

        <style>

            body {
                margin-top: 2vw;
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

        <?php $file = 'assets/config.php';
            //Use the function is_file to check if the config file already exists or not.
            if(!is_file($file)){
                copy('assets/config.php.sample', $file);
            } 
        ?>

        <?php include ('assets/config.php'); ?>
        <?php include ('assets/php/check.php') ;?>
        <?php include ('assets/php/gitinfo.php'); ?>

        <title><?php echo $config['title']; ?></title>
        
        <script src="assets/js/jquery.min.js"></script>

        <script src="assets/js/pace.js" async></script>

        <script type= "text/javascript">
            $(document).ready(function() {
                function update() {
                $.ajax({
                type: 'POST',
                url: 'assets/php/timestamp.php',
                timeout: 5000,
                success: function(data) {
                    $("#timer").html(data); 
                    window.setTimeout(update, 3000);
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
            </div> 

            <div id="center">
                <div id="centerinner" class="navbar-brand">
                    <div id="centertext" class="navbar-brand">
                        <a class="navbar-brand" href="<?php echo $config['siteurl']; ?>"> <?php echo $config['title']; ?></a>
                    </div>
                </div>
            </div>

            <div id="right" class="Column">
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
            
        <div id="services" class="container">
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

                            <script src="assets/js/clock.js"></script>

                        </div> 

                        <div id="statusloop">            
                            <!-- loop data goes here -->
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div id="system" class="system">
            <div id="stats" class="container centered">
                <!-- system badges go here -->
            </div>
        </div>

        <div id="footer">
        
            <p> <a class="footer a" href="https://github.com/monitorr/Monitorr" target="_blank"> Repo: Monitorr </a> // <a class="footer a" href="https://github.com/Monitorr/Monitorr/releases" target="_blank"> Version: <?php echo file_get_contents( "assets/js/version/version.txt" );?> </a> </p>

            <script src="assets/js/update.js"></script>

            <a class="footer a" id="version_check" style="cursor: pointer;">Check for Update</a>
            
        </div>

    </body>

</html>

