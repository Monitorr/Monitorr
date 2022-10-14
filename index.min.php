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

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Monitorr">
        <meta name="author" content="Monitorr">
        <meta name="version" content="php">
        <meta name="theme-color" content="#464646" />
        <meta name="theme_color" content="#464646" />

        <!-- Bootstrap core CSS -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom styles -->
        <link href="assets/css/main.css" rel="stylesheet">

        <style>

            body {
                margin-bottom: 1vw;
                overflow: scroll;
                overflow-x: hidden;
                overflow-y: auto !important;
            }

            #canvas {
                display: none !important;
                visibility: hidden !important;
            }

            :root {
                font-size: 12px !important;
            }

            ::-webkit-scrollbar {
                width: 0px;  /* remove scrollbar space */
                background: transparent;  /* make scrollbar invisible */
            }

            #summary {
                margin-top: -15px !important;
                z-index: 1000;
                font-size: .8rem;
            }

            #ajaxtimestamp {
                margin-top: -.5rem !important;
            }

            #ajaxmarquee {
                margin-top: -.5rem !important;
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
                width: 60% !important;
            }

            #center {
                position: absolute !important;
                height: 5rem;
                width: 100% !important;
                max-width: 40rem;
                left: 51% !important;
                padding-top: .5rem;
                background-color: inherit;
                box-shadow: 0px 0px 0px 0px #1F1F1F, 0px 0px 0px 0px #1F1F1F, 10px 0px 10px 0px #1F1F1F, -10px 0px 10px 2px #1F1F1F;
            }

            #stats {
                display: flex !important;
                margin: 0 auto;
                float: none !important;
                box-sizing: content-box;
                height: 2em !important;
                width: 40em !important;
                border-radius: 8px !important;
            }

            #uptime {
                width: auto !important;
                padding-bottom: .5rem !important;
            }

            #ping {
                padding-top: .5rem !important;
            }

            #services {
                width: 100% !important;
                margin-bottom: 4rem;
            }

            #slider {
                padding-top: .25rem !important;
            }

            #textslider {
                padding-top: .7rem !important;
                padding-bottom: .7rem !important;
            }

            #hd {
                display: contents;
            }

            .hdhidden {
                visibility: hidden;
                display: none !important;
            }

            #hdlabel1 {
                margin: 0 !important;
                font-size: .75rem !important;
            }

            #hdpercent1 {
                margin: 0 !important;
                font-size: .75rem !important;
            }

        </style>

        <script src="assets/js/jquery.min.js"></script>
		<script src="assets/js/custom/custom.js"></script>
            <!-- top loading bar function: -->
        <script src="assets/js/pace.js" async></script>

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
            }

            else {

                $datafile = 'assets/data/datadir.json';

                include_once ('assets/config/monitorr-data.php');

                $title = $jsonusers['sitetitle'];
            }
        ?>

        <title>
            <?php $title = $jsonusers['sitetitle']; echo $title . PHP_EOL; ?>
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
                    var dateString = res[0] + ' | ' + res[2] + " " + res[1];
                    var data = '<div class="dtg">' + time + ' ' + timeZone + '</div>';
                    data += '<div id="line">__________</div>';
                    data += '<div class="date datemin">' + dateString + '</div>';
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
                        //setTimeout(function() {syncServerTime()}, rftime); //delay is rftime
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

                    //Stop clock update when refresh toggle is disabled:

                $(":checkbox").change(function () {

                    rftime =
                        <?php
                            $rftime = $jsonsite['rftime'];
                            echo $rftime;
                        ?>

                    if ($(this).is(':checked')) {
                        nIntervId3 = setInterval(syncServerTime, rftime); //delay is rftime
                    } 
                    else {
                        clearInterval(nIntervId3);
                    }
                });
            });

        </script>

            <!-- services status update function: -->
        <script type="text/javascript">

            var nIntervId;
            var onload;

            rfsysinfo =
                <?php
                    $rfsysinfo = $jsonsite['rfsysinfo'];
                    echo $rfsysinfo;
                ?>

            function statusCheck() {
                console.log('Service check START | Interval: <?php echo $rfsysinfo; ?> ms');
                $("#statusloop").load('assets/php/loop.php');
                $("#stats").load('assets/php/systembadges.php');
            };

                //Stop service status update when refresh toggle is disabled:

            $(document).ready(function () {
                $(":checkbox").change(function () {

                    if ($(this).is(':checked')) {
                        nIntervId = setInterval(statusCheck, rfsysinfo);
                    } 
                    else {
                        clearInterval(nIntervId);
                    }
                });
            });

        </script>

            <!-- marquee offline function: -->
        <script>

             var nIntervId2;
             var onload;

            $(document).ready(function() {

                $(":checkbox").change(function () {

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
                            },
                            error: function(x, t, m) {
                                if(t==="timeout") {
                                    console.log("ERROR: marquee timeout");
                                    $('#ajaxmarquee').fadeIn();
                                } else {
                                    console.log("ERROR: marquee failed");
                                    $('#ajaxmarquee').fadeIn();
                                }
                            }
                        });
                    }

                    if ($(this).is(':checked')) {
                        updateSummary();
                        nIntervId2 = setInterval(updateSummary, rfsysinfo);
                        console.log("Auto refresh: Enabled | Interval: <?php echo $rfsysinfo; ?> ms");
                    } else {
                        clearInterval(nIntervId2);
                        console.log("Auto refresh: Disabled");
                    }
                });
                $('#buttonStart :checkbox').attr('checked', 'checked').change();
            });

        </script>

            <!-- Load analog clock: -->
        <script src="assets/js/clock.js" async></script>

    </head>

    <body onload="statusCheck()">

            <!-- Fade-in effect: -->
        <script>
            document.body.className += ' fade-out';
            $(function() {
                $('body').removeClass('fade-out');
            });
        </script>

            <!-- Append alert if service is down: -->
        <div id="summary"></div>

            <!-- Ajax timeout indicator: -->
        <div id="ajaxtimeout">
            <div id="ajaxtimestamp" title="Time sync timeout. Refresh page." style="display: none;">
                <i class="fa fa-fw fa-exclamation-triangle"></i>
            </div>
            <div id="ajaxmarquee" title="Offline marquee timeout. Refresh page." style="display: none;">
                <i class="fa fa-fw fa-exclamation-triangle"></i>
            </div>
        </div>

        <div id="headermin">

            <div id="left" class="Column">
                <div id="time">
                    <canvas id="canvas" width="120" height="120"></canvas>
                    <!-- <div class="dtg" id="timer"></div> -->
                    <div id="timer"></div>
                </div>
            </div>

            <div id="center" class="centermin">
                <div id="stats" class="container centered statsmin">
                    <!-- system badges go here -->
                </div>
            </div>

            <div id="right" class="Column">

                <div id="togglemin">
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

        </div>

            <!-- Check if datadir has been established: -->
        <?php

            $file = 'assets/data/datadir.json';

            if(!is_file($jsonfileuserdata)){

                echo '<div id="datdirerror">';
                    echo 'Data directory NOT detected';
                echo '</div>';
            }

            else {
            }
        ?>

        <div id="services" class="container">

            <div class="row">
                <div id="statusloop">
                    <!-- loop data goes here -->
                </div>
            </div>

        </div>

        <div id="footer">

            <p> <a class="footer a" href="https://github.com/monitorr/Monitorr" target="_blank" title="Monitorr Repo"> Monitorr </a> | <a class="footer a" href="https://github.com/Monitorr/Monitorr/releases" target="_blank" title="Monitorr Releases"> <?php echo file_get_contents( "assets/js/version/version.txt" );?> </a> </p>

        </div>

    </body>

</html>
