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
        <!-- <script type="text/javascript" src="../js/alpaca.min.js"></script> -->
        <script type="text/javascript" src="../js/alpaca.js"></script>
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

                img {
                    height: 10rem !important;
                }

            </style>

        <title>
            <?php 
                $str = file_get_contents('../data/site_settings-data.json');
                $json = json_decode($str, true);
                $title = $json['sitetitle'];
                echo $title . PHP_EOL;
            ?>
            | Service Settings
        </title>

        <?php include ('../config.php'); ?>
        <?php include ('../php/gitinfo.php'); ?>

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
                Service Settings
            </div>
        </div>



        <div id="servicesettings"></div>

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
                    $("#servicesettings").alpaca({
                        "connector": "custom",
                        "dataSource": "../data/services_settings-data.json?a=1",
                        "schemaSource": "../config/services-schema.json?a=1",
                        "options": {
                            "toolbarSticky": true,
                           // "items": {
                                "fields": {
                                    "serviceTitle": {
                                        "type": "text",
                                        "validate": true,
                                        "showMessages": true,
                                        "disabled": false,
                                        "hidden": false,
                                        "label": "Service Title",
                                        "helpers": [],
                                         "hideInitValidationError": false,
                                         "focus": false,
                                         "optionLabels": [],
                                         "name": "serviceTitle",
                                         "placeholder": "Service Name",
                                         "typeahead": {},
                                         "allowOptionalEmpty": false,
                                        "data": {},
                                         "autocomplete": false,
                                         "disallowEmptySpaces": false,
                                         "disallowOnlyEmptySpaces": false,
                                         "fields": {},
                                         "renderButtons": true,
                                         "attributes": {}
                                    },
                                    "image": {
                                        "type": "image",
                                         "validate": true,
                                        "showMessages": true,
                                        "disabled": false,
                                        "hidden": false,
                                        "label": "Service Image",
                                        "helpers": [],
                                         "hideInitValidationError": false,
                                         "focus": false,
                                         "optionLabels": [],
                                         "name": "image",
                                         "placeholder": "../img/monitorr.png",
                                         "typeahead": {},
                                         "allowOptionalEmpty": false,
                                         "data": {},
                                         "autocomplete": false,
                                         "disallowEmptySpaces": false,
                                         "disallowOnlyEmptySpaces": false,
                                         "fields": {},
                                         "renderButtons": true,
                                         "attributes": {}
                                    },
                                    "Type": {
                                        "type": "radio",
                                        "optionLabels": ["Both", "Curl Only", "Ping Only"],
                                        "showMessages": true,
                                        "disabled": false,
                                        "hidden": false,
                                        "label": "Check Type",
                                        "helpers": [],
                                         "hideInitValidationError": false,
                                         "focus": false,
                                         "name": "type",
                                         "placeholder": "both",
                                         "typeahead": {},
                                         "allowOptionalEmpty": false,
                                        "data": {},
                                         "autocomplete": false,
                                         "disallowEmptySpaces": false,
                                         "disallowOnlyEmptySpaces": false,
                                         "fields": {},
                                         "renderButtons": true,
                                         "attributes": {}
                                    },
                                    "checkurl": {
                                        "type": "url",
                                        "validate": true,
                                        "allowIntranet": true,
                                        "showMessages": true,
                                        "disabled": false,
                                        "hidden": false,
                                        "label": "Check URL",
                                        "helpers": [],
                                         "hideInitValidationError": false,
                                         "focus": false,
                                         "optionLabels": [],
                                         "name": "checkurl",
                                         "placeholder": "http://localhost:80",
                                         "typeahead": {},
                                         "allowOptionalEmpty": false,
                                        "data": {},
                                         "autocomplete": false,
                                         "disallowEmptySpaces": false,
                                         "disallowOnlyEmptySpaces": false,
                                         "fields": {},
                                         "renderButtons": true,
                                         "attributes": {}
                                    },
                                    "linkurl": {
                                        "type": "url",
                                        "validate": true,
                                        "allowIntranet": true,
                                        "showMessages": true,
                                        "disabled": false,
                                        "hidden": false,
                                        "label": "Link URL",
                                        "helpers": [],
                                         "hideInitValidationError": false,
                                         "focus": false,
                                         "optionLabels": [],
                                         "name": "linkurl",
                                         "placeholder": "http://localhost:80",
                                         "typeahead": {},
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
                           // },
                           "form": {
                                "attributes": {
                                    "action": "post_receiver-services.php",
                                    //"action": "fruits.php",
                                    "method": "post",
                                    "contentType": "application/json"
                                    //"enctype": "json"
                                },
                                "buttons": {
                                    "submit": {},
                                        /*   
                                            "submit": {
                                            "click": function() {

                                                //data = $('#servicesettings').alpaca().getValue();
                                                
                                                
                                                //        $.post('post_receiver-services.php',

                                                //             "=" + JSON.stringify(data, null, "  "),

                                                //            alert("settings Saved")
                                                //        ) 
                                                

                                                $.post({
                                                    url: 'post_receiver-services.php', 
                                                    data: data,
                                                    contentType: 'application/json',
                                                        success: function(data) {
                                                            alert(JSON.stringify(data));
                                                            alert("settings saved");
                                                        },
                                                    error: function(errorThrown){
                                                        console.log(errorThrown); 
                                                    } 
                                                }) 

                                                .fail(function() {
                                                        alert( "error" );
                                                    })   

                                                }
                                            }, 
                                        */
                                    "view": {
                                        "type": "button",
                                        "label": "View JSON",
                                        "value": "View JSON",
                                        "click": function() {
                                            alert(JSON.stringify(this.getValue(), null, "  "));
                                        }
                                    }
                                }
                            }
                        }
                    });
                });
            </script>



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