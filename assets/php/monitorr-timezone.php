<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <link type="text/css" href="../css/bootstrap.min.css" rel="stylesheet">
        <link type="text/css" href="../css/alpaca.min.css" rel="stylesheet">
        <link type="text/css" href="../css/main.css" rel="stylesheet">

        <meta name="theme-color" content="#464646" />
        <meta name="theme_color" content="#464646" />
        
        <script type="text/javascript" src="../js/jquery.min.js"></script>
        <script type="text/javascript" src="../js/pace.js" async></script>
        <script type="text/javascript" src="../js/handlebars.js"></script>
        <script type="text/javascript" src="../js/alpaca.min.js"></script>
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

            </style>

        <title>
            <?php 
                $str = file_get_contents('../data/user_preferences-data.json');
                $json = json_decode($str, true);
                $title = $json['sitetitle'];
                echo $title . PHP_EOL;
            ?>
            | User Preferences
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
                Site Settings
            </div>
        </div>

    <div id="timezoneform"> 

        <div id="timezone"></div>

            <script type="text/javascript">

                $(document).ready(function() {
                    var CustomConnector = Alpaca.Connector.extend({
                        buildAjaxConfig: function(uri, isJson) {
                            var ajaxConfig = this.base(uri, isJson);
                            ajaxConfig.headers = {
                                "ssoheader": "abcde12345"
                            };
                            return ajaxConfig;
                        }
                    });
                    Alpaca.registerConnectorClass("custom", CustomConnector);
                    $("#timezone").alpaca({
                        "connector": "custom",
                        "dataSource": "../data/timezone-data.json?a=1",
                        //"data": "(GMT) London",
                        "schema": {
                            "required": true,
                            //"defaut": "Europe/London"
                            // "enum": ["Europe/London"]
                        },
                        "options": {
                            "label": "Timezone",
                            "name": "timezone",
                            "type": "select",
                            "helper": "Choose the timezone that Monitorr will use for the UI",
                            "dataSource": "../config/timezone-list.json",
                             "removeDefaultNone": false,
                             "noneLabel": "-- Select --",
                           // "optionLabels": ["Vanilla Flavor", "Chocolate Flavor", "Coffee Flavor"]
                            "form": {
                                "attributes": {
                                    "action": "post_receiver-timezone.php",
                                    "method": "post",
                                },
                                "buttons": {
                                    "submit": {
                                        "type": "button",
                                        "label": "Submit",
                                        "name": "submit",
                                        "value": "submit",
                                        click: function(){
                                            var data = $('#timezone').alpaca().getValue();
                                            $.post({
                                                url: 'post_receiver-timezone.php', 
                                                data: $('#timezone').alpaca().getValue(),
                                                success: function(data) {
                                                    alert(JSON.stringify(data));
                                                    alert("settings saved!");
                                                    setTimeout(location.reload.bind(location), 100)
                                                },
                                                error: function(errorThrown){
                                                    console.log(errorThrown); 
                                                } 
                                            });
                                        }
                                    },
                                    "view": {
                                        "type": "button",
                                        "label": "View JSON",
                                        "value": "View JSON",
                                        "click": function() {
                                            alert(JSON.stringify(this.getValue(), null, "  "));
                                        }
                                    }
                                },
                            }
                        },
                    });
                });
            </script>
  
    </div>


        <div id="footer">

            <script src="../js/update.js" async></script>
            <script src="../js/update_auto.js" async></script>
        
            <p> <a class="footer a" href="https://github.com/monitorr/Monitorr" target="_blank"> Repo: Monitorr </a> | <a class="footer a" href="https://github.com/Monitorr/Monitorr/releases" target="_blank"> Version: <?php echo file_get_contents( "../js/version/version.txt" );?> </a> </p>

            <!-- <a class="footer a" id="version_check" style="cursor: pointer">Check for Update</a> -->
            
                <br>
            
            <div id="version_check_auto"></div>
            
        </div>

</body>

</html>