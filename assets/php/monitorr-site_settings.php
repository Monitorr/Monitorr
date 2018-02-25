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

                #centertext {
                    padding-bottom: 2rem !important;
                }

                label {
                    width: 100% !important;
                    max-width: 100% !important;
                }

                /* .control-label {
                    width: 100% !important;
                } */

            </style>

        <title>
            <?php 
                $str = file_get_contents('../data/user_preferences-data.json');
                $json = json_decode($str, true);
                $title = $json['sitetitle'];
                echo $title . PHP_EOL;
            ?>
            | Settings
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
                Monitorr Settings
            </div>
        </div>

    <div id="siteform"> 

        <div id="sitesettings"></div>

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
                    $("#sitesettings").alpaca({
                        "connector": "custom",
                        "dataSource": "../data/site_settings-data.json?a=1",
                        "schemaSource": "../config/site_settings-schema.json?a=1",
                        // "optionsSource": "./data/connector-custom-options.json?a=1",
                        // "viewSource": "../data/connector-custom-view.json?a=1",
                        "view": {
                            "parent": "bootstrap-edit-horizontal",
                            "layout": {
                                "template": './two-column-layout-template.html',
                                "bindings": {
                                    "rfsysinfo": "leftcolumn",
                                    "rftime": "leftcolumn",
                                    "pinghost": "leftcolumn",
                                    "pingport": "leftcolumn",
                                    "cpuok": "rightcolumn",
                                    "cpuwarn": "rightcolumn",
                                    "ramok": "rightcolumn",
                                    "ramwarn": "rightcolumn",
                                    "hdok": "rightcolumn",
                                    "hdwarn": "rightcolumn"
                                }
                            }
                        },
                        "options": {
                            "focus": false,
                            "type": "object",
                            "helpers": [],
                            "validate": true,
                            "disabled": false,
                            "showMessages": true,
                            "collapsible": false,
                            "legendStyle": "button",
                            "fields": {
                                "rfsysinfo": {
                                    "type": "number",
                                    "validate": true,
                                    "showMessages": true,
                                    "disabled": false,
                                    "hidden": false,
                                    "label": "Service & system refresh interval:",
                                    "helper": "Service & system info refresh interval in milliseconds.",
                                    "hideInitValidationError": false,
                                    "focus": false,
                                    "optionLabels": [],
                                    "name": "rfsysinfo",
                                    "placeholder": "5000",
                                    "typeahead": {},
                                    "size": "10",
                                    "allowOptionalEmpty": false,
                                    "data": {},
                                    "autocomplete": false,
                                    "disallowEmptySpaces": false,
                                    "disallowOnlyEmptySpaces": false,
                                    "fields": {},
                                    "renderButtons": true,
                                    "attributes": {}
                                },
                                "rftime": {
                                    "type": "number",
                                    "validate": true,
                                    "showMessages": true,
                                    "disabled": false,
                                    "hidden": false,
                                    "label": "Time refresh interval:",
                                    "helper": "UI clock display refresh interval in milliseconds.",
                                    "hideInitValidationError": false,
                                    "focus": false,
                                    "optionLabels": [],
                                    "name": "rftime",
                                    "placeholder": "5000",
                                    "typeahead": {},
                                    "size": "10",
                                    "allowOptionalEmpty": false,
                                    "data": {},
                                    "autocomplete": false,
                                    "disallowEmptySpaces": false,
                                    "disallowOnlyEmptySpaces": false,
                                    "fields": {},
                                    "renderButtons": true,
                                    "attributes": {}
                                },
                                "pinghost": {
                                    "type": "text",
                                    "validate": true,
                                    "showMessages": true,
                                    "disabled": false,
                                    "hidden": false,
                                    "label": "Ping host:",
                                    "helper": "URL or IP to ping for latency check. (WAN DNS provider is suggested)",
                                    "hideInitValidationError": false,
                                    "focus": false,
                                    "optionLabels": [],
                                    "name": "pinghost",
                                    "placeholder": "8.8.8.8",
                                    "typeahead": {},
                                        "size": "10",
                                    "allowOptionalEmpty": false,
                                    "data": {},
                                    "autocomplete": false,
                                    "disallowEmptySpaces": false,
                                    "disallowOnlyEmptySpaces": false,
                                    "fields": {},
                                    "renderButtons": true,
                                    "attributes": {}
                                },

                                "pingport": {
                                    "type": "number",
                                    "validate": true,
                                    "showMessages": true,
                                    "disabled": false,
                                    "hidden": false,
                                    "label": "Ping host port:",
                                    "helper": "Ping host port to use for latency check. (If using 8.8.8.8, value should be '53')",
                                    "hideInitValidationError": false,
                                    "focus": false,
                                    "optionLabels": [],
                                    "name": "pingport",
                                    "placeholder": "53",
                                    "typeahead": {},
                                    "size": "10",
                                    "allowOptionalEmpty": false,
                                    "data": {},
                                    "autocomplete": false,
                                    "disallowEmptySpaces": false,
                                    "disallowOnlyEmptySpaces": false,
                                    "fields": {},
                                    "renderButtons": true,
                                    "attributes": {}
                                },
                                "cpuok": {
                                    "type": "number",
                                    "validate": true,
                                    "showMessages": true,
                                    "disabled": false,
                                    "hidden": false,
                                    "label": "CPU OK color value:",
                                    "helper": "CPU% less than this will be green.",
                                    "hideInitValidationError": false,
                                    "focus": false,
                                    "optionLabels": [],
                                    "name": "cpuok",
                                    "placeholder": "50",
                                    "typeahead": {},
                                    "size": "10",
                                    "allowOptionalEmpty": false,
                                    "data": {},
                                    "autocomplete": false,
                                    "disallowEmptySpaces": false,
                                    "disallowOnlyEmptySpaces": false,
                                    "fields": {},
                                    "renderButtons": true,
                                    "attributes": {}
                                },
                                "cpuwarn": {
                                    "type": "number",
                                    "validate": true,
                                    "showMessages": true,
                                    "disabled": false,
                                    "hidden": false,
                                    "label": "CPU warning color value:",
                                    "helper": "CPU% less than this will be yellow.",
                                    "hideInitValidationError": false,
                                    "focus": false,
                                    "optionLabels": [],
                                    "name": "cpuwarn",
                                    "placeholder": "90",
                                    "typeahead": {},
                                    "size": "10",
                                    "allowOptionalEmpty": false,
                                    "data": {},
                                    "autocomplete": false,
                                    "disallowEmptySpaces": false,
                                    "disallowOnlyEmptySpaces": false,
                                    "fields": {},
                                    "renderButtons": true,
                                    "attributes": {}
                                },
                                "ramok": {
                                    "type": "number",
                                    "validate": true,
                                    "showMessages": true,
                                    "disabled": false,
                                    "hidden": false,
                                    "label": "RAM OK color value:",
                                    "helper": "RAM% less than this will be green.",
                                    "hideInitValidationError": false,
                                    "focus": false,
                                    "optionLabels": [],
                                    "name": "ramok",
                                    "placeholder": "50",
                                    "typeahead": {},
                                    "size": "10",
                                    "allowOptionalEmpty": false,
                                    "data": {},
                                    "autocomplete": false,
                                    "disallowEmptySpaces": false,
                                    "disallowOnlyEmptySpaces": false,
                                    "fields": {},
                                    "renderButtons": true,
                                    "attributes": {}
                                },
                                "ramwarn": {
                                    "type": "number",
                                    "validate": true,
                                    "showMessages": true,
                                    "disabled": false,
                                    "hidden": false,
                                    "label": "RAM warning color value:",
                                    "helper": "RAM% less than this will be yellow.",
                                    "hideInitValidationError": false,
                                    "focus": false,
                                    "optionLabels": [],
                                    "name": "ramwarn",
                                    "placeholder": "90",
                                    "typeahead": {},
                                    "size": "10",
                                    "allowOptionalEmpty": false,
                                    "data": {},
                                    "autocomplete": false,
                                    "disallowEmptySpaces": false,
                                    "disallowOnlyEmptySpaces": false,
                                    "fields": {},
                                    "renderButtons": true,
                                    "attributes": {}
                                },
                                "hdok": {
                                    "type": "number",
                                    "validate": true,
                                    "showMessages": true,
                                    "disabled": false,
                                    "hidden": false,
                                    "label": "HD OK color value:",
                                    "helper": "HD free % less than this will be green.",
                                    "hideInitValidationError": false,
                                    "focus": false,
                                    "optionLabels": [],
                                    "name": "hdok",
                                    "placeholder": "75",
                                    "typeahead": {},
                                    "size": "10",
                                    "allowOptionalEmpty": false,
                                    "data": {},
                                    "autocomplete": false,
                                    "disallowEmptySpaces": false,
                                    "disallowOnlyEmptySpaces": false,
                                    "fields": {},
                                    "renderButtons": true,
                                    "attributes": {}
                                },
                                "hdwarn": {
                                    "type": "number",
                                    "validate": true,
                                    "showMessages": true,
                                    "disabled": false,
                                    "hidden": false,
                                    "label": "HD warning color value:",
                                    "helper": "HD free % less than this will be yellow.",
                                    "hideInitValidationError": false,
                                    "focus": false,
                                    "optionLabels": [],
                                    "name": "hdwarn",
                                    "placeholder": "95",
                                    "typeahead": {},
                                    "size": "10",
                                    "allowOptionalEmpty": false,
                                    "data": {},
                                    "autocomplete": false,
                                    "disallowEmptySpaces": false,
                                    "disallowOnlyEmptySpaces": false,
                                    "fields": {},
                                    "renderButtons": true,
                                    "attributes": {}
                                }
                            },
                            "form": {
                                "attributes": {
                                    "action": "post_receiver-site_settings.php",
                                    "method": "post",
                                },
                                "buttons": {
                                    "submit": {
                                        "type": "button",
                                        "label": "Submit",
                                        "name": "submit",
                                        "value": "submit",
                                        click: function(){
                                            var data = $('#sitesettings').alpaca().getValue();
                                            $.post({
                                                url: 'post_receiver-site_settings.php', 
                                                data: $('#sitesettings').alpaca().getValue(),
                                                success: function(data) {
                                                    alert(JSON.stringify(data));
                                                    alert("settings saved!");
                                                    setTimeout(location.reload.bind(location), 500)
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

            <!-- <script src="../js/update.js" async></script> -->
            <!-- <script src="../js/update_auto.js" async></script> -->
        
            <p> <a class="footer a" href="https://github.com/monitorr/Monitorr" target="_blank"> Monitorr </a> | <a class="footer a" href="https://github.com/Monitorr/Monitorr/releases" target="_blank"> <?php echo file_get_contents( "../js/version/version.txt" );?> </a> </p>

            <!-- <a class="footer a" id="version_check" style="cursor: pointer">Check for Update</a> -->
            
            <!-- <div id="version_check_auto"></div> -->
            
        </div>

</body>

</html>