<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <link type="text/css" href="../../css/bootstrap.min.css" rel="stylesheet" >
    <link type="text/css" href="../../css/main.css" rel="stylesheet">
    <link type="text/css" href="../../css/formValidation.css" rel="stylesheet" />
    <script src="../../js/jquery.min.js"></script>
    <script src="parsley.min.js"></script>
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


                    <script type="text/javascript">

                        $(document).ready(function () {
                            $(function () {
                                $('#demo-form').parsley().on('field:validated', function() {
                                    var ok = $('.parsley-error').length === 0;
                                    $('.bs-callout-info').toggleClass('hidden', !ok);
                                    $('.bs-callout-warning').toggleClass('hidden', ok);
                                })
                                .on('form:submit', function() {
                                    return false; // Don't submit form for this demo
                                });
                            });
                        });
                    </script>


</head>

<body>


            <!-- <form id="demo-form" data-parsley-validate=""> -->
                <!-- <textarea id="demo-form" data-parsley-validate=""> -->
                <label for="fullname">Full Name * :</label>
                <textarea id="demo-form" class="form-control" name="fullname" data-parsley-trigger="hover" data-parsley-validate-if-empty data-parsley-required="true"></textarea>

                 <input type="submit" class="btn btn-default" value="validate">

            <!-- </form> -->

             








    <script>

        $(document).ready(function () {

            $('#datadirbtn').click(function () {

                $('#response').html("<font color='yellow'><b>Loading response...</b></font>");

                $.post({
                    url: './mkdirajax.php',
                    data: $(this).serialize(),
                    success: function (data) {
                        // alert("Directory Created successfully");
                        $('#response').html(data);
                    }
                })

                    .fail(function () {
                        alert("Posting failed (ajax1)");
                    });

                var datadir = $("#datadir").val();
                console.log('Submitted: ' + datadir);
                var url = "./mkdirajax.php";

                $.post(url, { datadir: datadir }, function (data) {
                    // alert("Directory Created successfully");
                    console.log('response: ' + data);
                    // alert('response: '+ data);
                    $('#response').html(data);

                })

                    .fail(function () {   // Why alert on UNIX? // CHANGE ME //
                        alert("Posting failed (ajax2)");
                    })

                return false;
            });
        });

    </script>

    <div id="dbwrapper">

        <hr>
        <br>


        <br>

        <form id="userForm">

            <div>
                <i class='fa fa-fw fa-folder-open'> </i>
                <input type='text' name='datadir' pattern="[/^\s+$/]+" title="Cannot contain spaces & must contain trailing slash"
                    fv-not-empty=" This field can't be empty" id="datadir" autocomplete="off" placeholder=' Data dir path' required>
                <!-- <i class='fa fa-fw fa-folder-open'> </i> <input type='text'  fv-advanced='{"regex": "/^\s$/", "message": "This field cannot contain spaces."}' fv-not-empty= " This field can't be empty" id="datadir" name='datadir' autocomplete="off" required placeholder='Data dir path' /> -->
                <br>
                <i class="fa fa-fw fa-info-circle"> </i>
                <i>
                    <?php echo "The current absolute path is: " . getcwd()  ?> </i>
            </div>

            <br>

            <div>
                <input type='submit' id="datadirbtn" class="btn btn-primary" value='Create' />
            </div>

        </form>

        <div id="loginerror">
            <i class="fa fa-fw fa-exclamation-triangle"> </i>
            <b> NOTE: </b>
            <br>
        </div>

        <div id="datadirnotes">
            <i>
                + The directory that is chosen must NOT already exist.
                <br> + Path value must include a trailing slash.
                <br> + For security purposes, this directory should NOT be within the webserver's filesystem hierarchy. However,
                if a path is chosen outside the webserver's filesystem, the PHP process must have read/write privileges to
                whatever location is chosen to create the data directory.
                <br> + Value must be an absolute path on the server's filesystem.
                <br> Good: c:\datadir\, /var/datadir/
                <br> Bad: wwwroot\datadir, ../datadir
            </i>
        </div>

        <br>

        <div id='response' class='dbmessage'></div>

    </div>

</body>

</html>