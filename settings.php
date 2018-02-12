<html>

<head>
    <link type="text/css" rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" />
    <link type="text/css" href="https://code.cloudcms.com/alpaca/1.5.23/bootstrap/alpaca.min.css" rel="stylesheet" />
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.11/handlebars.js"></script>
    <script type="text/javascript" src="https://code.cloudcms.com/alpaca/1.5.23/bootstrap/alpaca.min.js"></script>
</head>

<body>

    <!--     <div id="form"></div>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#form").alpaca({
                "schema": {
                    "title": "User Feedback",
                    "description": "What do you think about Alpaca?",
                    "type": "object",
                    "properties": {
                        "name": {
                            "type": "string",
                            "title": "Name"
                        },
                        "feedback": {
                            "type": "string",
                            "title": "Feedback"
                        },
                        "ranking": {
                            "type": "string",
                            "title": "Ranking",
                            "enum": ['excellent', 'ok', 'so so']
                        }
                    }
                }
            });
        });
    </script> -->


    <!--     <div id="field1"></div>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#field1").alpaca({
                "schema": {
                    "type": "string",
                    "title": "Pick an Action Hero"
                },
                "options": {
                    "type": "select",
                        "dataSource": [{
                            "value": "rambo",
                            "text": "John Rambo"
                        }, {
                            "value": "norris",
                            "text": "Chuck Norris"
                        }, {
                            "value": "arnold",
                            "text": "Arnold Schwarzenegger"
                        }]
                }
            });
        });
    </script> -->


    <!--     <div id="field2"></div>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#field2").alpaca({
                "dataSource": "/data/connector-custom-data.json",
                "schemaSource": "/data/connector-custom-schema.json",
                "optionsSource": "/data/connector-custom-options.json",
                "viewSource": "/data/connector-custom-view.json"
            });
        });
    </script> -->



    <!--     <div id="field40"></div>
    <script type="text/javascript">
        $(document).ready(function() {
            var appbase = "/dev/alpaca"
            var CustomConnector = Alpaca.Connector.extend({
                buildAjaxConfig: function(uri, isJson) {
                    var ajaxConfig = this.base(uri, isJson);
                    ajaxConfig.headers = {
                        "ssoheader": "abcdef1"
                    };
                    return ajaxConfig;
                }
            });
            Alpaca.registerConnectorClass("custom", CustomConnector);
            $("#field40").alpaca({
                "connector": "custom",
                "dataSource": "/data/connector-custom-data.json?a=1",
                "schemaSource": "/data/connector-custom-schema.json?a=1",
                "optionsSource": "/data/connector-custom-options.json?a=1",
                "viewSource": "/data/connector-custom-view.json?a=1"
            });
        });
    </script> -->


    <div id="field7"></div>
    <script type="text/javascript">
        // var data = $('#field7').alpaca().getValue();

        /*         $.ajax('/endpoint', {
                    contentType: 'application/json',
                    data: JSON.stringify(data)
                }) */

        $(document).ready(function() {
            $("#field7").alpaca({
                "schema": {
                    "title": "Your Information",
                    "type": "object",
                    "properties": {
                        "firstName": {
                            "title": "First Name",
                            "type": "string"
                        },
                        "lastName": {
                            "title": "Last Name",
                            "type": "string"
                        },
                        "age": {
                            "title": "Age",
                            "type": "integer",
                            "minimum": 0,
                            "maximum": 100
                        },
                        "preferences": {
                            "title": "Preferences",
                            "type": "string",
                            "enum": ["Non-Smoking", "Vegetarian", "Wheelchair Accessible", "Child Friendly"]
                        }
                    }
                },
                "options": {
                    "fields": {
                        "preferences": {
                            "type": "checkbox"
                        }
                    },
                    "form": {
                        "attributes": {
                            "action": "/dev/alpaca/data/datasource-example.json",
                            "method": "POST"
                        },
                        "buttons": {
                            "submit": {},
                            "reset": {},
                            "add": {
                                "title": "Add",
                                "click": function() {
                                    var itemId = "field-" + new Date().getTime();
                                    var itemSchema = {
                                        "type": "string"
                                    };
                                    var itemOptions = {
                                        "label": itemId
                                    };
                                    var itemData = "Hello World";
                                    var insertAfterId = "preferences";
                                    this.topControl.addItem(itemId, itemSchema, itemOptions, itemData, insertAfterId, function(item) {
                                        // alert("added!");
                                    });
                                }
                            },
                            "remove": {
                                "title": "Remove Last",
                                "click": function() {
                                    var lastChild = this.topControl.lastChild();
                                    if (lastChild) {
                                        this.topControl.removeItem(lastChild.propertyId, function() {
                                            //alert("removed!");
                                        });
                                    }
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>



<!--     <script>
        var $firstName = $(".name"),
            $caption = $(".caption");
        var object = {
            name: $firstName.val(),
            caption: $caption.val()
        }

        var params = JSON.stringify(object);

        $.ajax({
            type: 'POST',
            data: params,
            url: 'save_to_json.php',
            success: function(data) {

            },
            error: function() {

            }
        });
    </script> -->



</body>

</html>