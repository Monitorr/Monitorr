<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <title>Monitorr | Login</title>
    <link type="text/css" href="../css/bootstrap.min.css" rel="stylesheet" />
    <link type="text/css" href="../css/main.css" rel="stylesheet">
    <script src="../js/jquery.min.js"></script>

    <style type="text/css">

        body { 
            color: white;
            background-color: #1F1F1F;
        }

        .navbar-brand { 
            cursor: default;
        }

        .wrapper { 
            width: 30rem;
            margin-top: 10%;
            margin-left: auto;
            margin-right: auto;
            padding: 1rem; 
        }

    </style>

    
</head>

    <body>

            <script>
                $(document).ready(function(){

                    $('#userForm').submit(function(){
                    
                        $('#response').html("<b>Loading response...</b>");
                        
                        $.post({
                            url: 'mkdirajax.php',
                            data: $(this).serialize(),
                            // data =  {'action': clickBtnValue};  //CHANGEME
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

            

<!--             <script>
                $(document).ready(function(){

                   $('#select').click(function(){

                        $('#response').html("<b>Loading response...</b>");

                        var clickBtnValue = $(this).val();
                        var ajaxurl = 'mkdirajax.php',
                        data =  {'action': clickBtnValue};
                        $.post(ajaxurl, data, function (response) {
                            alert("action performed successfully");
                            $('#response').html("action performed successfully");
                        })

                        .fail(function() {
                            alert( "Posting failed" );
                            $('#response').html("action failed");
                        });
                    });

                });
            </script> -->


        <form id='userForm'>
            Desired Data Directory:
                <br> <br>
            <div><input type='text' name='datadir' placeholder='Data Dir Path' /></div>

                <br>

            <div><input type='submit' class="btn btn-primary" value='Submit' /></div>

        </form>

                <br>
        <div id='response'></div>


    </body>

</html>




