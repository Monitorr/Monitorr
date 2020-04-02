<!DOCTYPE html>
<html lang="en">

    <!--
         Monitorr | Settings page
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
        <link type="text/css" href="assets/css/main.css" rel="stylesheet">
        <link type="text/css" href="assets/data/css/custom.css" rel="stylesheet">

        <meta name="theme-color" content="#464646" />
        <meta name="theme_color" content="#464646" />

        <script type="text/javascript" src="assets/js/jquery.min.js"></script>
        <script type="text/javascript" src="assets/js/pace.js" async></script>
        <!-- <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script> -->

        <style>

            body {
                margin: auto;
                padding-left: 2rem;
                padding-right: 1rem;
                padding-bottom: 1rem;
                /* overflow-y: scroll !important;  */
                overflow-x: hidden !important;
                background-color: #1F1F1F;
            }

            .navbar-brand {
                font-size: 2rem !important;
                font-weight: 500;
            }

            #summary {
                position: relative !important;
                margin: auto;
                margin-top: 0rem !important;
                margin-bottom: 1rem;
                width: 16.5rem !important;
                font-size: .8rem;
                line-height: 1.5rem;
                border-radius: .2rem;
                box-shadow: 3px 3px 3px black !important;
            }

            #ajaxmarquee {
                margin-top: 1rem;
                margin-left: 0;
            }

            #ajaxtimestamp {
                margin-top: 1rem;
                margin-left: -1rem;
            }

            legend {
                color: white;
            }

            body::-webkit-scrollbar {
                width: .75rem;
                background-color: #252525;
            }

            body::-webkit-scrollbar-track {
                -webkit-box-shadow: inset 0 0 .25rem rgba(0, 0, 0, 0.3);
                box-shadow: inset 0 0 .25rem rgba(0, 0, 0, 0.3);
                border-radius: .75rem;
                background-color: #252525;
            }

            body::-webkit-scrollbar-thumb {
                border-radius: .75rem;
                -webkit-box-shadow: inset 0 0 .25rem rgba(0, 0, 0, .3);
                box-shadow: inset 0 0 .25rem rgba(0, 0, 0, .3);
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
                padding-bottom: 1.5rem !important;
            }

            #footer {
                position: fixed !important;
                bottom: 0 !important;
            }

            a:link {
                background-color: transparent !important;
            }

        </style>

        <?php

            $datafile = 'assets/data/datadir.json';
            $str = file_get_contents($datafile);
            $json = json_decode( $str, true);
            $datadir = $json['datadir'];
            $jsonfileuserdata = $datadir . 'user_preferences-data.json';

            if(!is_file($jsonfileuserdata)){

                $path = "assets/";

                include_once ('assets/config/monitorr-data-default.php');

                $title = $jsonusers['sitetitle'];

                $rftime = $jsonsite['rftime'];

                $timezone = $jsonusers['timezone'];
            }

            else {

                $datafile = 'assets/data/datadir.json';

                include_once ('assets/config/monitorr-data.php');

                $title = $jsonusers['sitetitle'];

                $rftime = $jsonsite['rftime'];
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

             <!-- Clock functions: -->
        <script>

	        <?php
                //initial values for clock:
                $timezone = $jsonusers['timezone'];
                $dt = new DateTime("now", new DateTimeZone("$timezone"));
                $timeStandard = (int)($jsonusers['timestandard'] === "True" ? true : false);
                $timezone_suffix = '';
                if (!$timeStandard) {
                    $dateTime = new DateTime();
                    $dateTime->setTimeZone(new DateTimeZone($timezone));
                    $timezone_suffix = $dateTime->format('T');
                }
                $serverTime = $dt->format("D d M Y H:i:s");
	        ?>

            var nIntervId3;
            var onload;

            var serverTime = "<?php echo $serverTime;?>";
            var date = new Date(serverTime); 
            var timestandard = <?php echo $timeStandard;?>;
            var timeZone = "<?php echo $timezone_suffix;?>";
            var rftime = <?php echo $jsonsite['rftime'];?>;

            function updateTime() {
                setInterval(function () {
                    var res = date.toString().split(" ");
                    var time = res[4];
                    var timeSplit = time.split(":");
                    if(timestandard) {
                        time = parseInt((timeSplit[0] > 12) ? (timeSplit[0] - 12) : timeSplit[0]) + ":" + timeSplit[1] + ":" + timeSplit[2];
                        if(timeSplit[0] >= 12) {
                            time += " PM";
                        } else {
                            time += " AM";
                        }
                    }
                    var dateString = res[0] + ' | ' + res[2] + " " + res[1] + "<br>" + res[3];
                    var data = '<div class="dtg">' + time + ' ' + timeZone + '</div>';
                    data += '<div id="line">__________</div>';
                    data += '<div class="date">' + dateString + '</div>';
                    $("#timer").html(data);
                }, 1000);
            }

                // update UI clock with server time:

            function syncServerTime() {
                console.log('Monitorr time sync START | Interval: '+ rftime +' ms');
                $.ajax({
                    url: "assets/php/timestamp.php",
                    type: "GET",
                    timeout: 4000,
                    success: function (response) {
                        var response = $.parseJSON(response);
                        serverTime = response.serverTime;
                        timestandard = parseInt(response.timestandard);
                        timeZone = response.timezoneSuffix;
                        rftime = parseInt(response.rftime);
                        date = new Date(serverTime);
                        $('#ajaxtimestamp').fadeOut();
                    },
                    error: function(x, t, m) {
                        if(t==="timeout") {
                            console.log("ERROR: Time sync timeout");
                            $('#ajaxtimestamp').fadeIn();
                        } else {
                            console.log("ERROR: timestamp failed");
                            $('#ajaxtimestamp').fadeIn();
                        }
                    }
                });
            }

            $(document).ready(function() {
                syncServerTime(); 
                updateTime();

                    // sync UI clock with server time:
                function update() {

                    rftime =
                        <?php
                            $rftime = $jsonsite['rftime'];
                            echo $rftime;
                        ?>

                    nIntervId3 = setInterval(syncServerTime, rftime); //delay is rftime
                }
                update();
            });

        </script>
        
            <!-- marquee offline function: -->
        <script>

            var nIntervId2;
            var onload;
            var current = -1;

            function updateSummary() {

                 console.log('Service offline check START');

                rfsysinfo =
                    <?php
                        $rfsysinfo = $jsonsite['rfsysinfo'];
                        echo $rfsysinfo;
                    ?>

                $.ajax({
                    type: 'POST',
                    url: 'assets/php/marquee.php',
                    data: {
                        current: current
                    },
                    timeout: 5000,
                    success: function(data) {
                        if(data){
                            result = $.parseJSON(data);
                            console.log(result);
                            $("#summary").fadeOut(function() {
                                $(this).html(result[0]).fadeIn();
                            });
                            current = result[1];
                        }
                        else {
                            current = -1;
                            $("#summary").hide();
                        }
                        $('#ajaxmarquee').fadeOut();
                        window.setTimeout(updateSummary, rfsysinfo);
                    },
                    error: function(x, t, m) {
                        if(t==="timeout") {
                            //alert("ERROR: marquee timeout");
                            console.log("ERROR: marquee timeout");
                            $('#ajaxmarquee').fadeIn();
                        } else {
                            console.log("ERROR: marquee failed");
                            $('#ajaxmarquee').fadeIn();
                        }
                    }
                });
            }

        </script>

            <!-- Load monitorr-info frame on settings page onload: -->
        <script>
            $(function() {
                document.getElementById("includedContent").innerHTML='<object type="text/html" class="object" data="assets/php/monitorr-info.php" ></object>';
            });
        </script>

        <script>

            var nIntervId2;
            var onload;
            var current = -1;
            
            function updateSummaryManual() {

                console.log('Service offline check START');

                $.ajax({
                    type: 'POST',
                    url: 'assets/php/marquee.php',
                    data: {
                        current: current
                    },
                    timeout: 3000,
                    success: function(data) {
                        if(data){
                            result = $.parseJSON(data);
                            console.log(result);
                            $("#summary").fadeOut(function() {
                                $(this).html(result[0]).fadeIn();
                            });
                            current = result[1];
                        }

                        else {
                            current = -1;
                            $("#summary").hide();
                        }
                        $('#ajaxmarquee').fadeOut();
                    },
                    error: function(x, t, m) {
                        if(t==="timeout") {
                            //alert("ERROR: marquee timeout");
                            console.log("ERROR: marquee timeout");
                            $('#ajaxmarquee').fadeIn();
                        } else {
                            console.log("ERROR: marquee failed");
                            $('#ajaxmarquee').fadeIn();
                        }
                    }
                });
            }

        </script>

        <script>

            $(document).ready(function() {
                updateSummary();
            });

        </script>

            <!-- Load analog clock: -->
        <script src="assets/js/clock.js" async></script>

    </head>

    <body>

            <!-- Fade-in effect: -->
        <script>
            document.body.className += ' fade-out';
            $(function() {
                $('body').removeClass('fade-out'); 
            });
        </script>

        <div id="ajaxtimeout">
            <div id="ajaxtimestamp" title="Time sync timeout. Refresh page." style="display: none;">
                <i class="fa fa-fw fa-exclamation-triangle"></i>
            </div>
            <div id="ajaxmarquee" title="Offline marquee timeout. Refresh page." style="display: none;">
                <i class="fa fa-fw fa-exclamation-triangle"></i>
            </div>
        </div>


        <div id ="settingscolumn" class="settingscolumn">

            <div id="settingsbrand">
                <div class="navbar-brand">
                    <?php
                        echo $title . PHP_EOL;
                    ?>
                </div>
            </div>

                <!-- Append marquee alert if service is down: -->
            <div id="summary"></div>

            <div id="left" class="Column">
                <div id="clock">
                    <canvas id="canvas" width="120" height="120"></canvas>
                    <!-- <div class="dtg" id="timer"></div> -->
                    <div id="timer"></div>
                </div>
            </div>

            <div id="wrapper">

                <!-- Sidebar -->
                <nav class="navbar navbar-inverse navbar-fixed-top" id="sidebar-wrapper" role="navigation">

                    <div class="settingstitle">
                        Settings
                    </div>

                    <ul class="nav sidebar-nav">

                        <li>
                            <a href ="#info" onclick="load_info()"><i class="fa fa-fw fa-info"></i> Info </a>
                        </li>
                        <li>
                            <a href ="#user-preferences" onclick="load_preferences()"><i class="fa fa-fw fa-cog"></i>  User Preferences </a>
                        </li>
                        <li>
                            <a href ="#monitorr-settings" onclick="load_settings()"><i class="fa fa-fw fa-cog"></i>  Monitorr Settings </a>
                        </li>
                        <li>
                            <a href ="#services-configuration" onclick="load_services()"><i class="fa fa-fw fa-cog"></i> Services Configuration  </a>
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

        <div id ="includedContent">

            <script>
                function load_info() {
                    document.getElementById("includedContent").innerHTML='<object  type="text/html" class="object" data="assets/php/monitorr-info.php" ></object>';
                    updateSummaryManual();
                }
            </script>

            <script>
                function load_preferences() {
                    document.getElementById("includedContent").innerHTML='<object type="text/html" class="object" data="assets/php/monitorr-user_preferences.php" ></object>';
                    updateSummaryManual();
                }
            </script>

            <script>
                function load_settings() {
                    document.getElementById("includedContent").innerHTML='<object type="text/html" class="object" data="assets/php/monitorr-site_settings.php" ></object>';
                    updateSummaryManual();
                }
            </script>

            <script>
                function load_services() {
                    document.getElementById("includedContent").innerHTML='<object type="text/html" class="object" data="assets/php/monitorr-services_settings.php" ></object>';
                    updateSummaryManual();
                }
            </script>

        </div>

            <!-- Fire loop.php once on page load to get services status: -->
        <script>

            $(function() {
                console.log('Service check START');
                $("#serviceshidden").load('assets/php/loopsettings.php');
            });

        </script>

        <div id="serviceshidden"></div>

    </body>

</html>
