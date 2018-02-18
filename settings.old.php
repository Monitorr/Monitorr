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

<!-- <SCRIPT>
    function passWord() {
        var testV = 1;
        var pass1 = prompt('Please Enter Your Password',' ');
        while (testV < 3) {
        if (!pass1) 
        history.go(-1);
        if (pass1.toLowerCase() == "letmein") {
        alert('You Got it Right!');
        window.open('protectpage.html');
        break;
        } 
        testV+=1;
        var pass1 = 
        prompt('Access Denied - Password Incorrect, Please Try Again.','Password');
        }
        if (pass1.toLowerCase()!="password" & testV ==3) 
        history.go(-1);
        return " ";
    } 
</SCRIPT> -->

<!-- <CENTER>
<FORM>
<input type="button" value="Enter Protected Area" onClick="passWord()">
</FORM>
</CENTER> -->


Site Settings:

<div id="field10"></div>

    <script type="text/javascript">
        $(document).ready(function() {

            $("#field10").alpaca({
                "schema": {
                    "type": "object",
                    "properties": {
                        "sitetitle": {
                            "type": "string",
                            "title": "Site Title"
                        },

                        "siteurl": {
                            "type": "string",
                            "title": "Site URL"
                        },

                        "timezone": {
                            "type": "string",
                            "title": "Timezone"
                        }
                    }
                },
                "options": {

                    "form": {
                        "attributes": {
                            "action": "post_receiver.php",
                            "method": "post",
                            // "enctype": "multipart/form-data"
                        },

                        "buttons": {
                            "submit": {
                                type: 'button',
                                "label": "submit",
                                click: function(){

                                    var data = $('#field10').alpaca().getValue();
                                    
                                    $.post('post_receiver.php', $('#field10').alpaca().getValue())
                                    
                                    alert(JSON.stringify(this.getValue(), null, "  "))
                                }
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



Services:

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
                            // "enctype": "multipart/form-data"
                        },

                        "buttons": {
                            "submit": {
                                type: 'button',
                                "label": "submit",
                                click: function(){

                                    var data = $('#field7').alpaca().getValue();
                                    //var data = $(JSON.stringify(this.getValue(), null, "  "));
                                    //var val = this.getValue();
                                    //var promise = JSON.stringify(this.getValue(), null, "  ");
                                   
                                    //var data = JSON.stringify(this.getValue(), null, "  ");
                                    //data: JSON.stringify(data)

                                   //  $.post('post_receiver.php', $('#field7').alpaca().getValue())

                                    $.post('post_receiver.php', {
                                        data
                                     })
                                    
                                    alert(JSON.stringify(this.getValue(), null, "  "))
                                
                                }
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


<div id='response'></div>

</body>

</html>