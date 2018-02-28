<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="manifest" href="webmanifest.json">
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
        <link rel="apple-touch-icon" href="favicon.ico">
        <link type="text/css" href="../css/bootstrap.min.css" rel="stylesheet">
        <link type="text/css" href="../css/alpaca.min.css" rel="stylesheet">
        <link type="text/css" href="../css/main.css" rel="stylesheet">

        <meta name="theme-color" content="#464646" />
        <meta name="theme_color" content="#464646" />
        
        <script type="text/javascript" src="../js/jquery.min.js"></script>
        <script type="text/javascript" src="../js/pace.js" async></script>
        <script type="text/javascript" src="../js/handlebars.js"></script>
        <script type="text/javascript" src="../js/alpaca.min.js"></script>
        <!-- <script type="text/javascript" src="../js/alpaca.js"></script> -->
        <script type="text/javascript" src="../js/bootstrap.min.js"></script>

            <style>

                body {
                    margin: 2vw !important;
                    overflow-y: auto; 
                    overflow-x: hidden;
                    color: white !important;
                    font-size: 1rem !important;  /* ** CHANGE IN ALL SUB SETTINGS PAGES ** */
                }

                /* :root {
                    font-size: 12px !important;
                } */

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

                .navbar-brand {   /* ** ADD TO ALL SETTINGS PAGES ** */
                    cursor: default;
                }

                img {
                    height: 6rem !important;
                }

            </style>

        <title>
            <?php 
                $str = file_get_contents('../data/user_preferences-data.json');
                $json = json_decode($str, true);
                $title = $json['sitetitle'];
                echo $title . PHP_EOL;
            ?>
            | Service Config
        </title>

        <?php include ('../config.php'); ?>
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
                Services Configuration
            </div>
        </div>

    <div id="serviceform"> 

        <form id="servicesettings" onsubmit="formsubmit()">

            <!-- <button onclick="formsubmit()" id="myBtn2" label="Submit" title="Form Submit" /> -->
            <!-- <input id="myBtn2" type="submit" value="Submit" /> -->

        </form>

        <!-- <button onclick="formsubmit()" id="myBtn2" label="Submit" title="Form Submit" ></button> -->
         <!-- <button onclick="topFunction()" id="myBtn" title="Go to top"></button> -->

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
                         //**     NOT WORKING    *//
                        // "view": {
                        //     "parent": "bootstrap-edit-horizontal",
                        //     "layout": {
                        //         "template": './two-column-layout-template.html',
                        //         "bindings": {
                        //             "serviceTitle": "leftcolumn",
                        //             "image": "leftcolumn",
                        //             "checkurl": "rightcolumn",
                        //             "linkurl": "rightcolumn",
                        //             "type": "rightcolumn"                                    
                        //         }
                        //     }
                        // }, 
                        //"view": "bootstrap-edit-horizontal",
                        "options": {
                            "toolbarSticky": true,
                            "collapsible": true,
                            "actionbar": {
                                "showLabels": true,
                                "actions": [{
                                    "label": "Add Service",
                                    "action": "add"
                                }, {
                                    "label": "Remove Service",
                                    "action": "remove"
                                }, {
                                    "label": "Move UP",
                                    "action": "up",
                                    "enabled": true
                                }, {
                                    "label": "Move Down",
                                    "action": "down"
                                }, {
                                    "label": "Clear",
                                    "action": "clear",
                                    "iconClass": "fa fa-cancel",
                                    "click": function(key, action, itemIndex) {
                                        var item = this.children[itemIndex];
                                        item.setValue("");
                                    }
                                }
                                ]
                            },
                           "items": {
                                "fields": {
                                    "serviceTitle": {
                                        "type": "text",
                                        "validate": true,
                                        "showMessages": true,
                                        "disabled": false,
                                        "hidden": false,
                                        "label": "Service Title:",
                                        //"helpers": ["Name of Service"],
                                        //"helper": "Name of Service",
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
                                        "label": "Service Image:",
                                        //"helpers": ["Icon/image representation of service"],
                                        //"helper": "Icon/image representation of service",
                                         "hideInitValidationError": false,
                                         "focus": false,
                                         "optionLabels": [],
                                         "name": "image",
                                         "styled": true,
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
                                    "checktype": {
                                        "type": "radio",
                                        "optionLabels": [" Standard", " Ping Only"],
                                        "showMessages": true,
                                        "disabled": false,
                                        "hidden": false,
                                        "label": "Check Type:",
                                        //"helpers": ["Standard: Services that can be accessed via HTTP / Ping: Any service that is listening on defined port."],
                                        "helper": "Standard: Services that can be accessed via HTTP / Ping: Any service that is listening on defined port.",
                                        "hideInitValidationError": false,
                                        "focus": false,
                                        "name": "checktype",
                                        "placeholder": " Standard",
                                        "typeahead": {},
                                        "allowOptionalEmpty": false,
                                        "removeDefaultNone": true,
                                        "hideNone": true,
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
                                        "label": "Check URL:",
                                        //"helpers": ["URL to check status"],
                                        "helper": "URL to check service status. (Port is required!)",
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
                                        "validate": false,
                                        "allowIntranet": true,
                                        "showMessages": true,
                                        "disabled": false,
                                        "hidden": false,
                                        "label": "Link URL:",
                                        //"helpers": ["URL that will be linked to service"],
                                        "helper": "URL that will be linked to service from the UI. ('Link URL' field values are not applied if using 'ping only' option)",
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
                           },
                           "form": {
                                "attributes": {
                                    "action": "post_receiver-services.php",
                                    "method": "post",
                                    "contentType": "application/json"
                                    //"enctype": "json"
                                },
                                "buttons": {
                                    "submit": {
                                       // "type": 'button',
                                       // "label": "submit",
                                        "click": function formsubmit() {

                                            var data = $('#servicesettings').alpaca().getValue();

                                            $.post('post_receiver-services.php', {

                                                 //JSON.stringify(data, null, "  "),
                                                data
                                                },
                                                                                              
                                                alert(JSON.stringify(data, null, "  ")),
                                                alert("settings saved"),
                                                //console.log(data),
                                                // setTimeout(location.reload.bind(location), 500)
                                            )
                                        
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
                                }
                            }
                        }
                    });
                });
            </script>

    </div>

                <!-- scroll to top   -->
                
            <button onclick="topFunction()" id="myBtn" title="Go to top"></button>

            <script>
                 
                // When the user scrolls down 20px from the top of the document, show the button
                window.onscroll = function() {scrollFunction()};

                function scrollFunction() {
                    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                        document.getElementById("myBtn").style.display = "block";
                    } else {
                        document.getElementById("myBtn").style.display = "none";
                    }
                }

                // When the user clicks on the button, scroll to the top of the document
                function topFunction() {
                    document.body.scrollTop = 0;
                    document.documentElement.scrollTop = 0;
                }

        </script>

        <div id="footer">

            <p> <a class="footer a" href="https://github.com/monitorr/Monitorr" target="_blank"> Monitorr </a> | <a class="footer a" href="https://github.com/Monitorr/Monitorr/releases" target="_blank"> <?php echo file_get_contents( "../js/version/version.txt" );?> </a> </p>
            
        </div>

</body>

</html>