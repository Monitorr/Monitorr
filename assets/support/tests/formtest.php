<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <link type="text/css" href="../../css/bootstrap.min.css" rel="stylesheet" >
    <link type="text/css" href="../../css/main.css" rel="stylesheet">
    <link type="text/css" href="../../css/formValidation.css" rel="stylesheet" />
    <script src="../../js/jquery.min.js"></script>
    <script src="../../js/formValidation.js"></script>
     <script src="../../js/jquery.validate.min.js"></script>

    <!-- <script src="parsley.min.js"></script> -->
    <!-- <script src="../../js/formValidation.js"></script> -->


        <style>

            body {
                margin: auto;
                color: white;
                padding-left: 2rem;
                padding-right: 1rem;
                padding-bottom: 1rem;
                /* overflow-y: scroll !important;  */
                overflow-x: hidden !important;
                /* color: white !important; */
                background-color: #1F1F1F !important;
            }

            </style>




<!--                 <script>

                    $(document).ready(function () {

                        $('#datadirbtn').submit(function(){

                            //$('#datadirbtn').validate({
                            $.validate({
                                debug: true,
                                submitHandler: function(form) {
                                    $.ajax({
                                        url: '../../config/_installation/mkdirajax.php',
                                        data: $(this).serialize(),
                                        //type: form.method,
                                        data: $(form).serialize(),
                                        success: function(data) {
                                            console.log('Submitted: '+ data);
                                            $('#response').html(data);
                                        }            
                                    });

                                    return false;

                                }
                            });
                         });

                    });
                 </script> -->






</head>

<body>



                <script>

                    $(document).ready(function () {

                        //$('#datadirbtn').submit(function(){

                            //$('#datadirbtn').validate({
                            $('#userForm').validate({

                                $("#Textbox").rules("add", { regex: "^[a-zA-Z'.\\s]{1,40}$" }),

                                debug: true,
                                submitHandler: function(form) {
                                    $(form).ajax({
                                        url: '../../config/_installation/mkdirajax.php',
                                        data: $(this).serialize(),
                                        type: form.method,
                                        success: function(data) {
                                            console.log('Submitted: '+ data);
                                            $('#response').html(data);
                                        }            
                                    });

                                    return false;

                                }
                            });
                         //});

                    });
                 </script>


                        <div id=response></div>

                            <form id="userForm">
                                   
                                   <input type='text' name='datadir' pattern=".*[\\//]+$" title="Cannot contain spaces & must contain a trailing slash" fv-advanced='{"regex": "\\s", "regex_reverse": true, "message": "  Value cannot contain spaces"}' fv-not-empty=" This field can't be empty" id="datadir" autocomplete="off" placeholder=' Data dir path' required>

                                    <input type='submit' id="datadirbtn" class="btn btn-primary" value='Create' />

                            </form>








</body>

</html>