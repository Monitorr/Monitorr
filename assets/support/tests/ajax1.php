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
        <link rel="manifest" href="webmanifest.json">
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
        <link rel="apple-touch-icon" href="favicon.ico">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Monitorr">
        <meta name="author" content="Monitorr">
        <meta name="version" content="php">
        <meta name="theme-color" content="#464646" />
        <meta name="theme_color" content="#464646" />


        <!-- Bootstrap core CSS -->

        <!-- Fonts from Google Fonts -->
        <link href='//fonts.googleapis.com/css?family=Lato:300,400,900' rel='stylesheet' type='text/css'>

        <!-- Custom styles -->
        <link href="../css/main.css" rel="stylesheet">
        <link href="../data/css/custom.css" rel="stylesheet">
       

        <style>

            body {
                margin-top: 2rem;
                margin-bottom: 2vw;
                overflow-y: auto; 
                overflow-x: hidden;
                background-color: #1F1F1F;
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

            | Monitorr
        </title>
        
        <script src="../js/jquery.min.js"></script>



    </head>

    <body >

        <p id="response"></p>



            <script>
                    var data;
                    $.ajax({
                        //dataType: "json",
                        url: 'response1.php',
                        data: data,
                        success: function (data) {
                            // begin accessing JSON data here
                            console.log(data);
                            document.getElementById("response").innerHTML = data;
                        },

                        error: function(errorThrown){
                            console.log(errorThrown);
                            document.getElementById("response").innerHTML = "GET failed (ajax)";
                            alert( "GET failed (ajax)" ); 
                        },

                    });

            </script>


    </body>

</html>

