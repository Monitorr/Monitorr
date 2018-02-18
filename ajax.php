<html>

<head>
    <link type="text/css" rel="stylesheet" href="assets/css/bootstrap.css" />
    <link href="assets/css/main.css" rel="stylesheet">
    <script type="text/javascript" src="assets/js/jquery.min.js"></script>

        <style>

            body {
                margin-top: 2vw;
                margin-bottom: 2vw;
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

    <!-- our form -->  
    <form id='userForm'>
        <div><input type='text' name='firstname' placeholder='firstname' /></div>
        <div><input type='text' name='lastname' placeholder='Lastname' /></div>
        <div><input type='text' name='email' placeholder='Email' /></div>
        <div><input type='submit' value='Submit' /></div>
    </form>
 
    <!-- where the response will be displayed -->
    <div id='response'></div>
 

        <script>
            $(document).ready(function(){

                $('#userForm').submit(function(){
                
                    $('#response').html("<b>Loading response...</b>");
                    
                    $.post({
                        url: './assets/php/post_receiver.php',
                        data: $(this).serialize(),
                        //contentType: 'application/json',
                        success: function(data){
                            alert("settings saved");
                            $('#response').html(data);
                        }
                    })

                    .fail(function() {
                        alert( "Posting failed." );
                    });

                    return false;
                });
            });

        </script>



</body>

</html>