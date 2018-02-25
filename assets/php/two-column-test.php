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
        <!-- <script type="text/javascript" src="../js/alpaca.js"></script> -->
        <script type="text/javascript" src="../js/bootstrap.min.js"></script>

            <style>

                body {
                    margin: 2vw !important;
                    overflow-y: auto; 
                    overflow-x: hidden;
                    color: white !important;
                }

                :root {
                    font-size: 12px !important;
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
                $str = file_get_contents('../data/user_preferences-data.json');
                $json = json_decode($str, true);
                $title = $json['sitetitle'];
                echo $title . PHP_EOL;
            ?>
            | Service Settings
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
                Service Settings
            </div>
        </div>

    <div id="serviceform"> 

        <div id="field7"></div>

            <script type="text/javascript">
                $(document).ready(function() {
                    $("#field7").alpaca({
                        "schema": {
                            "type": "array",
                            "items": {
                                "type": "object",
                                "properties": {
                                    "type": {
                                        "enum": ["internal", "external"]
                                    },
                                    "url": {
                                        "type": "string",
                                        "format": "uri"
                                    }
                                }
                            }
                        },
                        "options": {
                            "toolbarSticky": true,
                            "items": {
                                "fields": {
                                    "type": {
                                        "label": "Type",
                                        "optionLabels": ["Internal", "External"]
                                    },
                                    "url": {
                                        "label": "URL"
                                    }
                                }
                            },
                            "form": {
                                "attributes": {
                                    "action": "save",
                                    "method": "post",
                                    "enctype": "multipart/form-data"
                                },
                                "buttons": {
                                    "submit": {}
                                }
                            }
                        },
                        "view": {
                            "parent": "bootstrap-edit-horizontal",
                            "layout": {
                                "template": './two-column-layout-template.html',
                                "bindings": {
                                    "type": "#leftcolumn",
                                    "url": "#rightcolumn"
                                }
                            }
                        }
                    });
                });
            </script>

    </div>

        <div id="footer">

            <!-- <script src="../js/update.js" async></script> -->
            <!-- <script src="../js/update_auto.js" async></script> -->
        
            <p> <a class="footer a" href="https://github.com/monitorr/Monitorr" target="_blank"> Repo: Monitorr </a> | <a class="footer a" href="https://github.com/Monitorr/Monitorr/releases" target="_blank"> Version: <?php echo file_get_contents( "../js/version/version.txt" );?> </a> </p>

            <!-- <a class="footer a" id="version_check" style="cursor: pointer">Check for Update</a> -->
            
            <!-- <div id="version_check_auto"></div> -->
            
        </div>

</body>

</html>