<html>

<head>
    <link type="text/css" rel="stylesheet" href="assets/css/bootstrap.css" />
    <link type="text/css" href="https://code.cloudcms.com/alpaca/1.5.23/bootstrap/alpaca.min.css" rel="stylesheet" />
    <link href="assets/css/main.css" rel="stylesheet">
    <script type="text/javascript" src="assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.11/handlebars.js"></script>
    <script type="text/javascript" src="https://code.cloudcms.com/alpaca/1.5.23/bootstrap/alpaca.min.js"></script>

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



</head>

<body>




    
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
                                "enum": ["Curl", "Ping"]
                            },
                            "check url": {
                                "type": "string",
                                "format": "uri"
                            },
                            "link url": {
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
                                "optionLabels": ["Curl", "Ping"]
                            },
                            "check url": {
                                "label": "Check URL"
                            },
                            "link url": {
                                "label": "Link URL"
                            }
                        }
                    },
                    "form": {
                        "attributes": {
                            "action": "post_receiver.php",
                            "method": "post",
                            "target": "_self",
                            // "url": "settings.php",
                            "enctype": "multipart/form-data"
                        },

                        "buttons": {
                            "submit": {
                            },

                            "view": {
                                "label": "View JSON",
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




</body>

</html>