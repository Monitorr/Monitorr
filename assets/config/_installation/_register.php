<?php

        /**
         * Class OneFileLoginApplication
         *
         * An entire php application with user registration, login and logout in one file.
         * Uses very modern password hashing via the PHP 5.5 password hashing functions.
         * This project includes a compatibility file to make these functions available in PHP 5.3.7+ and PHP 5.4+.
         *
         * @author Panique
         * @link https://github.com/panique/php-login-one-file/
         * @license http://opensource.org/licenses/MIT MIT License
         */

            /**
             * @var string define data directory path //
             */


    class OneFileLoginApplication
    {

        /**
         * @var string Type of used database (currently only SQLite, but feel free to expand this with mysql etc)
         */
        private $db_type = "sqlite"; //

        /**
         * @var string define data directory path //
         */

        // private $db_sqlite_path = "../data/users.db";


        /**
         * @var object Database connection
         */
        private $db_connection = null;

        /**
         * @var bool Login status of user
         */
        private $user_is_logged_in = false;

        /**
         * @var string System messages, likes errors, notices, etc.
         */
        public $feedback = "";
        public $feedbackerror = "";
        public $feedbacksuccess = "";


        /**
         * Does necessary checks for PHP version and PHP password compatibility library and runs the application
         */


        public function __construct()
        {

                $str = file_get_contents( "../../data/datadir.json" );

                $json = json_decode( $str, true);

                $datadir = $json['datadir'];

            $this->datadir = $datadir;

                $datafile = $datadir . 'users.db';

                $db_sqlite_path = $datafile;


            $this->db_sqlite_path = $db_sqlite_path;


            if ($this->performMinimumRequirementsCheck()) {
                $this->runApplication();
            }
        }

        /**
         * Performs a check for minimum requirements to run this application.
         * Does not run the further application when PHP version is lower than 5.3.7
         * Does include the PHP password compatibility library when PHP version lower than 5.5.0
         * (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
         * @return bool Success status of minimum requirements check, default is false
         */
        private function performMinimumRequirementsCheck()
        {
            if (version_compare(PHP_VERSION, '5.3.7', '<')) {
                echo "Sorry, Simple PHP Login does not run on a PHP version older than 5.3.7 !";
            } elseif (version_compare(PHP_VERSION, '5.5.0', '<')) {
                require_once("vendor/password_compatibility_library.php");
                return true;
            } elseif (version_compare(PHP_VERSION, '5.5.0', '>=')) {
                return true;
            }
            // default return
            return false;
        }

        /**
         * This is basically the controller that handles the entire flow of the application.
         */
        public function runApplication()
        {

            $this->doRegistration();
            $this->showPageRegistration();

        }

        /**
         * Creates a PDO database connection (in this case to a SQLite flat-file database)
         * @return bool Database creation success status, false by default
         */
        private function createDatabaseConnection()
        {
            try {
                $this->db_connection = new PDO($this->db_type . ':' . $this->db_sqlite_path);
                return true;
            } catch (PDOException $e) {
                $this->feedback = "PDO database connection problem: " . $e->getMessage();
            } catch (Exception $e) {
                $this->feedback = "General problem: " . $e->getMessage();
            }
            return false;
        }

        /**
         * Handles the flow of the login/logout process. According to the circumstances, a logout, a login with session
         * data or a login with post data will be performed
         */
        private function performUserLoginAction()
        {
            if (isset($_GET["action"]) && $_GET["action"] == "logout") {
                $this->doLogout();
            } elseif (!empty($_SESSION['user_name']) && ($_SESSION['user_is_logged_in'])) {
                $this->doLoginWithSessionData();
            } elseif (isset($_POST["login"])) {
                $this->doLoginWithPostData();
            }
        }

        /**
         * Simply starts the session.
         * It's cleaner to put this into a method than writing it directly into runApplication()
         */
        private function doStartSession()
        {
            if(session_status() == PHP_SESSION_NONE) session_start();
        }

        /**
         * Set a marker (NOTE: is this method necessary ?)
         */
        private function doLoginWithSessionData()
        {
            $this->user_is_logged_in = true; // ?
        }

        /**
         * Process flow of login with POST data
         */
        private function doLoginWithPostData()
        {
            if ($this->checkLoginFormDataNotEmpty()) {
                if ($this->createDatabaseConnection()) {
                    $this->checkPasswordCorrectnessAndLogin();
                }
            }
        }

        /**
         * Logs the user out
         */
        private function doLogout()
        {
            $_SESSION = array();
            session_destroy();
            $this->user_is_logged_in = false;
            $this->feedback = "You were just logged out.";
            header("location: ../../../settings.php");
        }

        /**
         * The registration flow
         * @return bool
         */
        private function doRegistration()
        {
            if ($this->checkRegistrationData()) {
                if ($this->createDatabaseConnection()) {
                    $this->createNewUser();
                }
            }
            // default return
            return false;
        }

        /**
         * Validates the login form data, checks if username and password are provided
         * @return bool Login form data check success state
         */
        private function checkLoginFormDataNotEmpty()
        {
            if (!empty($_POST['user_name']) && !empty($_POST['user_password'])) {
                return true;
            } elseif (empty($_POST['user_name'])) {
                $this->feedback = "Username field was empty.";
            } elseif (empty($_POST['user_password'])) {
                $this->feedback = "Password field was empty.";
            }
            // default return
            return false;
        }

        /**
         * Checks if user exits, if so: check if provided password matches the one in the database
         * @return bool User login success status
         */
        private function checkPasswordCorrectnessAndLogin()
        {
            // remember: the user can log in with username or email address
            $sql = 'SELECT user_name, user_email, user_password_hash
                    FROM users
                    WHERE user_name = :user_name OR user_email = :user_name
                    LIMIT 1';
            $query = $this->db_connection->prepare($sql);
            $query->bindValue(':user_name', $_POST['user_name']);
            $query->execute();

            // Btw that's the weird way to get num_rows in PDO with SQLite:
            // if (count($query->fetchAll(PDO::FETCH_NUM)) == 1) {
            // Holy! But that's how it is. $result->numRows() works with SQLite pure, but not with SQLite PDO.
            // This is so crappy, but that's how PDO works.
            // As there is no numRows() in SQLite/PDO (!!) we have to do it this way:
            // If you meet the inventor of PDO, punch him. Seriously.
            $result_row = $query->fetchObject();
            if ($result_row) {
                // using PHP 5.5's password_verify() function to check password
                if (password_verify($_POST['user_password'], $result_row->user_password_hash)) {
                    // write user data into PHP SESSION [a file on your server]
                    $_SESSION['user_name'] = $result_row->user_name;
                    $_SESSION['user_email'] = $result_row->user_email;
                    $_SESSION['user_is_logged_in'] = true;
                    $this->user_is_logged_in = true;
                    return true;
                } else {
                    $this->feedback = "Wrong password.";
                }
            } else {
                $this->feedback = "This user does not exist.";
            }
            // default return
            return false;
        }

        /**
         * Validates the user's registration input
         * @return bool Success status of user's registration data validation
         */
        private function checkRegistrationData()
        {
            // if no registration form submitted: exit the method
            if (!isset($_POST["register"])) {
                return false;
            }

            // validating the input
            if (!empty($_POST['user_name'])
                && strlen($_POST['user_name']) <= 64
                && strlen($_POST['user_name']) >= 2
                && preg_match('/^[a-z\d]{2,64}$/i', $_POST['user_name'])
            // && !empty($_POST['user_email'])
            // && strlen($_POST['user_email']) <= 64
            // && filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)
                && !empty($_POST['user_password_new'])
                && strlen($_POST['user_password_new']) >= 6
                && !empty($_POST['user_password_repeat'])
                && ($_POST['user_password_new'] === $_POST['user_password_repeat'])
            ) {
                // only this case return true, only this case is valid
                return true;
            } elseif (empty($_POST['user_name'])) {
                $this->feedbackerror = "Empty Username";
            } elseif (empty($_POST['user_password_new']) || empty($_POST['user_password_repeat'])) {
                $this->feedbackerror = "Empty Password";
            } elseif ($_POST['user_password_new'] !== $_POST['user_password_repeat']) {
                $this->feedbackerror = "Password and password repeat are not the same";
            } elseif (strlen($_POST['user_password_new']) < 6) {
                $this->feedbackerror = "Password has a minimum length of 6 characters";
            } elseif (strlen($_POST['user_name']) > 64 || strlen($_POST['user_name']) < 2) {
                $this->feedbackerror = "Username cannot be shorter than 2 or longer than 64 characters";
            } elseif (!preg_match('/^[a-z\d]{2,64}$/i', $_POST['user_name'])) {
                $this->feedbackerror = "Username does not fit the name scheme: only a-Z and numbers are allowed, 2 to 64 characters";
            } elseif (empty($_POST['user_email'])) {
                $this->feedbackerror = "Email cannot be empty";
            } elseif (strlen($_POST['user_email']) > 64) {
                $this->feedbackerror = "Email cannot be longer than 64 characters";
            } elseif (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
                $this->feedbackerror = "Your email address is not in a valid email format";
            } else {
                $this->feedbackerror = "An unknown error occurred.";
            }

            // default return
            return false;

        }


        /**
         * Creates a new user.
         * @return bool Success status of user registration
         */
        private function createNewUser()
        {
            // remove html code etc. from username and email
            $user_name = htmlentities($_POST['user_name'], ENT_QUOTES);
            $user_email = htmlentities($_POST['user_email'], ENT_QUOTES);
            $user_password = $_POST['user_password_new'];
            // crypt the user's password with the PHP 5.5's password_hash() function, results in a 60 char hash string.
            // the constant PASSWORD_DEFAULT comes from PHP 5.5 or the password_compatibility_library
            $user_password_hash = password_hash($user_password, PASSWORD_DEFAULT);

            $sql = 'SELECT * FROM users WHERE user_name = :user_name OR user_email = :user_email';
            $query = $this->db_connection->prepare($sql);
            $query->bindValue(':user_name', $user_name);
            $query->bindValue(':user_email', $user_email);
            $query->execute();

            // As there is no numRows() in SQLite/PDO (!!) we have to do it this way:
            // If you meet the inventor of PDO, punch him. Seriously.
            $result_row = $query->fetchObject();
            if ($result_row) {
                $this->feedback = "Sorry, that username / email is already taken. Please choose another one.";
            } else {
                $sql = 'INSERT INTO users (user_name, user_password_hash, user_email)
                        VALUES(:user_name, :user_password_hash, :user_email)';
                $query = $this->db_connection->prepare($sql);
                $query->bindValue(':user_name', $user_name);
                $query->bindValue(':user_password_hash', $user_password_hash);
                $query->bindValue(':user_email', $user_email);
                // PDO's execute() gives back TRUE when successful, FALSE when not
                // @link http://stackoverflow.com/q/1661863/1114320
                $registration_success_state = $query->execute();

                if ($registration_success_state) {
                    $this->feedbacksuccess = '<b>User credentials have been created successfully. <br> <a href="../../php/monitorr-info.php" title="Monitorr Settings">Log in here</a> </b>';

                    return true;
                } else {
                    $this->feedbackerror = "ERROR: registration failed. Please check the webserver PHP logs and try again.";
                }
            }
            // default return
            return false;
        }

        /**
         * Simply returns the current status of the user's login
         * @return bool User's login status
         */
        public function getUserLoginStatus()
        {
            return $this->user_is_logged_in;
        }

        /**
         * Simple demo-"page" that will be shown when the user is logged in.
         * In a real application you would probably include an html-template here, but for this extremely simple
         * demo the "echo" statements are totally okay.
         */
        private function showPageLoggedIn()
        {
            if ($this->feedback) {
                echo $this->feedback . "<br/><br/>";
            }

            //  header("location: assets/php/info.php");
            echo 'Hello ' . $_SESSION['user_name'] . ', you are logged in.<br/><br/>';
        echo '<a href="' . $_SERVER['SCRIPT_NAME'] . '?action=logout">Log out</a>';
        }

                
        public function runregister() 
        {
            ?>

                <!--  START registration form -->

                    <!DOCTYPE html>
                    <html lang="en">

                        <head>

                            <meta charset="UTF-8">
                            <!-- <link type="text/css" href="../../css/bootstrap.min.css" rel="stylesheet" > -->
                            <!-- <link type="text/css" href="../../css/main.css" rel="stylesheet"> -->
                            <link type="text/css" href="../../css/formValidation.css" rel="stylesheet" />
                            <script src="../../js/jquery.min.js"></script>
                            <script src="../../js/formValidation.js"></script>

                            <script>  // Disable button if input field is invalid:

                                $(document).ready(function(){
                                    $('#datadir').each(
                                        function(){
                                            var val = $(this).val().trim();
                                            if (val == ''){
                                               $( '#datadirbtn' ).prop( 'disabled', true );
                                            }
                                        }
                                    );
                                    $('#dbfile').each(
                                        function(){
                                            var val = $(this).val().trim();
                                            if (val == ''){
                                               $( '#dbbtn' ).prop( 'disabled', true );
                                            }
                                        }
                                    );
                                    $('#login_input_password_repeat').each(
                                        function(){
                                            var val = $(this).val().trim();
                                            if (val == ''){
                                               $( '#registerbtn' ).prop( 'disabled', true );
                                            }
                                        }
                                    );
                                });

                            </script>

                        </head>

                        <body>

                            <!-- datadir create button: -->
                            <script>

                                $(document).ready(function(){

                                    $('#datadirbtn').click(function(){

                                        $('#response').html("<font color='yellow'><b>Creating data directory...</b></font>");

                                        var datadir = $("#datadir").val();
                                        console.log('submitted: '+ datadir);
                                        var url ="./mkdirajax.php";

                                        $.post(url, { datadir: datadir }, function(data){
                                            // alert("Directory Created successfully");
                                            console.log('mkdirajax: '+ data);
                                            $('#response').html(data);
                                            $('#userwrapper').load(document.URL +  ' #userwrapper');
                                        })

                                        .fail(function() {
                                            alert( "Posting failed (ajax)" );
                                            console.log("Posting failed (ajax)");
                                        })

                                        return false;
                                    });
                                });

                            </script>

                            <!-- db create button: -->
                            <script>

                                $(document).ready(function(){

                                    $('#dbbtn').click(function(){

                                        $('#response').html("<font color='yellow'><b>Creating user database...</b></font>");

                                        var dbfile = $("#dbfile").val();
                                        console.log('submitted: '+ dbfile);
                                        var url ="./mkdbajax.php";

                                        $.post(url, { dbfile: dbfile }, function(data){
                                            // alert("Directory Created successfully");
                                            console.log('mkdbajax: '+ data);
                                            $('#response').html(data);
                                            $('#userwrapper').load(document.URL +  ' #userwrapper');

                                        })

                                        .fail(function() {
                                            alert( "Posting failed (ajax)" );
                                            console.log("Posting failed (ajax)");
                                        })

                                        return false;
                                    });
                                });

                            </script>


                            <div class="wrapperregistration">

                                <div class="navbar-brand">
                                    Monitorr | Registration
                                </div>

                                    <br> <br> <hr>

                                <div id="loginmessage">

                                    <i class="fa fa-fw fa-info-circle"> </i> This registration process will perform the following actions: <br><br>

                                </div>

                                <div id="reginstructions">

                                    1-	Establish a data directory which will contain three json files with your custom settings, and a user database file. <br>
                                    2-	Create a data directory definition file in the Monitorr installation directory which defines where your data directory is established on the webserver. <br>
                                    3-	Copy the three default json settings files from the local Monitorr repository to the established data directory. <br>
                                    4-	Create a sqlite user database file in the established data directory. <br>
                                    5-	Create a user. <br>
                                    +	The above actions must complete successfully and in succession in order for Monitorr to function properly. <br>
                                    +	If you have any problems during the registration process, please check the <strong> <a href="https://github.com/Monitorr/Monitorr/wiki" target="_blank" class="toolslink" title="Monitorr Wiki"> Monitorr Wiki </a>. </strong> <br>

                                </div>
                            </div>

                            <?php

                                $datafile = '../../data/datadir.json';
                                $str = file_get_contents($datafile);
                                $json = json_decode( $str, true);
                                $datadir1 = $json['datadir'];
                                $datadirdetect = $datadir1 . 'monitorr_data_directory.txt';

                                if (!is_file($datadirdetect)) {

                            ?>
                                    <!--  START datadir create form -->

                                        <div id="dbwrapper">

                                            <hr>

                                            <div id='dbdir' class='loginmessage'>
                                                Create data directory, copy default data json files, and create user database file in defined directory:
                                            </div>
                                                <br>

                                            <?php

                                                $datafile = '../../data/datadir.json';
                                                $str = file_get_contents($datafile);
                                                $json = json_decode( $str, true);
                                                $datadir = $json['datadir'];
                                                $jsonfileuserdata = $datadir . 'user_preferences-data.json';

                                                if(is_file($jsonfileuserdata)){

                                                    echo '<div id="loginerror">';
                                                        echo '<i class="fa fa-fw fa-exclamation-triangle"> </i><b> WARNING: An existing data directory is detected at: '; echo $datadir; echo ' <br> If an additional data directory is created, the current data directory will NOT be altered, however, Monitorr will use all default resources from the newly created data directory. </b> <br>';

                                                    echo '</div>';
                                                }

                                                else {
                                                }
                                            ?>

                                            <form id="datadirform">

                                                <div>
                                                    <i class='fa fa-fw fa-folder-open'> </i> <input type='text' name='datadir' id="datadir" fv-not-empty=" This field can't be empty" fv-advanced='{"regex": "\\s", "regex_reverse": true, "message": "  Value cannot contain spaces"}' fv-valid-func="$( '#datadirbtn' ).prop( 'disabled', false )" fv-invalid-func="$( '#datadirbtn' ).prop( 'disabled', true )" autocomplete="off" placeholder=' Data dir path' required>
                                                        <br>
                                                    <i class="fa fa-fw fa-info-circle"> </i> <i><?php echo "The current absolute path is: " . getcwd()  ?> </i>
                                                </div>
                                                        <br>
                                                <div>
                                                    <input type='input' id="datadirbtn" class="btn btn-primary"  title="Create data directory" value='Create' /> 
                                                </div>

                                            </form>

                                            <div id="loginerror">
                                                    <i class="fa fa-fw fa-exclamation-triangle"> </i><b> NOTE: </b> <br>
                                            </div>

                                            <div id="datadirnotes">
                                                    <i>
                                                + The directory that is chosen must NOT already exist, however CAN be a sub directory of an exisiting directory.
                                                    <br>
                                                + Path value must include a trailing slash.
                                                    <br>
                                                + For security purposes, this directory should NOT be within the webserver's filesystem hierarchy. However, if a path is chosen outside the webserver's filesystem, the PHP process must have read/write privileges to whatever location is chosen to create the data directory.
                                                    <br>
                                                + Value must be an absolute path on the server's filesystem with the exception of Docker - use a relative path with trailing slash.
                                                    <br>
                                                Good:  c:\datadir\, /var/datadir/
                                                    <br>
                                                Bad: wwwroot\datadir, ../datadir
                                                    </i>
                                            </div>

                                                <br>

                                            <div id='response' class='dbmessage'></div>

                                        </div>

                                    <!--  END datadir create form -->

                            <?php

                                }

                                else  {

                                    $dbfile = $datadir1 . 'users.db';

                                    if (!is_file($dbfile)) {
                            ?>
                                        <!--  START  db create form -->


                                            <div id="dbcreatewrapper">

                                                <hr><br>

                                                <?php

                                                    $datafile = '../../data/datadir.json';

                                                    $file = '../../data/datadir.json';

                                                    if(is_file($file)){

                                                        include_once ('../monitorr-data.php');

                                                        $datadir = $json['datadir'];

                                                        echo '<div id="loginmessage">';
                                                            echo '<i class="fa fa-fw fa-exclamation-triangle"> </i><b> NOTE: </b> An existing data directory is detected at: '; echo $datadir; echo ' <br> By clicking "create" below, a user database will be created in the data directory specified below while leaving the JSON setting files in tact. <br> After the user database is created you will be able to create a user, log in, and edit the Monitorr settings. <br>';
                                                                echo '<br>';
                                                        echo '</div>';
                                                    }

                                                    else {

                                                    }
                                                ?>

                                                <form id="dbform">

                                                    <div>
                                                        <i class='fa fa-fw fa-folder-open'> </i> <input type='text' name='dbfile' id="dbfile" fv-not-empty=" This field can't be empty" fv-advanced='{"regex": "\\s", "regex_reverse": true, "message": "  Value cannot contain spaces"}' autocomplete="off" value=" <?php echo  $datadir = $json['datadir']; ?>"  required readonly>
                                                    </div>
                                                            <br>
                                                    <div>
                                                        <input type='submit' id="dbbtn" class="btn btn-primary" title="Create user database" value='Create' />
                                                    </div>

                                                </form>

                                                    <br>

                                                <div id='response' class='dbmessage'></div>

                                            </div>

                                        <!--  END  db create form -->

                            <?php
                                    }

                                    else  {

                            ?>
                                        <!--  START multi form -->

                                            <div id="multiform">

                                                <div id="multiwarning">

                                                    <?php
                                                        
                                                        $datafile = '../../data/datadir.json';

                                                        $file = '../../data/datadir.json';

                                                        if(is_file($file)){

                                                            include_once ('../monitorr-data.php');

                                                            $datadir = $json['datadir'];

                                                                echo '<i class="fa fa-fw fa-exclamation-triangle"> </i><b> WARNING: An existing data directory is detected at: '; echo $datadir; echo ' <br> If an additional data directory is created, the current directory will NOT be altered, however, Monitorr will use all resources from the newly created data directory. </b> <br>';

                                                        }

                                                        else {
                                                        }
                                                    ?>

                                                </div>

                                                <hr><br>

                                                <table id='regmulti'>

                                                    <tr>
                                                        <th>
                                                            <div id='datadirheading' class='multiheading'>
                                                                Create new data directory:
                                                            </div>
                                                        </th>

                                                        <th>
                                                            <div id='dbheading' class='multiheading'>
                                                                Create new user database:
                                                            </div>
                                                        </th>
                                                    </tr>

                                                    <tr>
                                                        <td>

                                                            <div id="datadirmulti">

                                                                <form id="datadirform">

                                                                    <div>
                                                                        <i class='fa fa-fw fa-folder-open'> </i> <input type='text' name='datadir' id="datadir" fv-not-empty=" This field can't be empty" fv-advanced='{"regex": "\\s", "regex_reverse": true, "message": "  Value cannot contain spaces"}' fv-valid-func="$( '#datadirbtn' ).prop( 'disabled', false )" fv-invalid-func="$( '#datadirbtn' ).prop( 'disabled', true )" autocomplete="off" placeholder=' Data dir path' required>
                                                                            <br>
                                                                        <i class="fa fa-fw fa-info-circle"> </i> <i><?php echo "The current absolute path is: " . getcwd()  ?> </i>
                                                                    </div>
                                                                            <br>
                                                                    <div>
                                                                        <input type='submit' id="datadirbtn" class="btn btn-primary" title="Create data directory" value='Create'/>
                                                                    </div>

                                                                </form>

                                                                <div id="loginerror">
                                                                        <i class="fa fa-fw fa-exclamation-triangle"> </i><b> NOTE: </b> <br>
                                                                </div>

                                                                <div id="datadirnotes">
                                                                        <i>
                                                                    + The directory that is chosen must NOT already exist, however CAN be a sub directory of an exisiting directory.
                                                                        <br>
                                                                    + Path value must include a trailing slash.
                                                                        <br>
                                                                    + For security purposes, this directory should NOT be within the webserver's filesystem hierarchy. <br> However, if a path is chosen outside the webserver's filesystem, the PHP process must have read/write privileges to whatever location is chosen to create the data directory.
                                                                        <br>
                                                                    + Value must be an absolute path on the server's filesystem with the exception of Docker - use a relative path with trailing slash.
                                                                        <br>
                                                                    Good:  c:\datadir\, /var/datadir/
                                                                        <br>
                                                                    Bad: wwwroot\datadir, ../datadir
                                                                        </i>
                                                                </div>

                                                            </div>

                                                        </td>

                                                        <td>

                                                            <div id="dbcreatewrappermulti">

                                                                <form id="dbform">

                                                                    <div>
                                                                        <i class='fa fa-fw fa-folder-open'> </i> <input type='text' name='dbfilemulti' id="dbfile" fv-not-empty=" This field can't be empty" fv-advanced='{"regex": "\\s", "regex_reverse": true, "message": "  Value cannot contain spaces"}' fv-valid-func="$( '#dbbtn' ).prop( 'disabled', false )" fv-invalid-func="$( '#dbbtn' ).prop( 'disabled', true )" autocomplete="off" value=" <?php echo  $datadir = $json['datadir']; ?>"  required>
                                                                            <br>
                                                                        <i class="fa fa-fw fa-info-circle"> </i> <i><?php echo "The current data directory path is: " . $datadir = $json['datadir'];  ?> </i>
                                                                            <br>
                                                                    </div>
                                                                            <br>
                                                                    <div>
                                                                        <input type='submit' id="dbbtn" class="btn btn-primary" title="Create user database" value='Create' />
                                                                    </div>

                                                                </form>

                                                                <?php

                                                                    $datafile = '../../data/datadir.json';

                                                                    if(is_file($datafile)){

                                                                        include_once ('../monitorr-data.php');

                                                                        $datadir = $json['datadir'];

                                                                        echo '<div id="loginerror">';
                                                                                echo '<i class="fa fa-fw fa-exclamation-triangle"> </i><b> NOTE: </b> <br>';
                                                                        echo "</div>";

                                                                            echo '<div id="datadirnotes"> ';
                                                                                    echo "<i>";
                                                                                echo "+ An existing data directory is detected at: $datadir ";
                                                                                    echo "<br>";
                                                                                echo '+ By clicking "create" above, a user database will be created in the data directory specified. ';
                                                                                        echo "<br>";
                                                                                echo '+ The location of the new user database MUST be located in the data directory indicated above. ';
                                                                                        echo "<br>";
                                                                                echo '+ If there is an existing user database in this directory, it will be renamed to "users.db.old" and a new user database will be created.';
                                                                                    echo "<br>";
                                                                                echo '+ All setting JSON files in the current data directory will be left in tact.';
                                                                                    echo "<br>";
                                                                                echo '+ After the new user database is created, you will be prompted to create new user credentials.';
                                                                                    echo "<br>";
                                                                            echo "</div> ";
                                                                    }

                                                                    else {

                                                                    }
                                                                ?>

                                                                    <br>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                </table>

                                                <div id='response' class='dbmessage'></div>

                                            </div>

                                        <!--  END multi form-->

                            <?php

                                    }
                                }

                            ?>

                            <!--  START create user form -->

                                <div id="userwrapper">

                                    <?php

                                        //Create user:

                                        $datafile = '../../data/datadir.json';
                                        $str = file_get_contents($datafile);
                                        $json = json_decode( $str, true);
                                        $datadir = $json['datadir'];
                                        $dbfile3 = $datadir . 'users.db';

                                        if(is_file($dbfile3)){

                                            echo '<hr><br>';

                                            echo '<div id="loginmessage">';
                                                echo 'Create a user in the user database: '; echo $datadir; echo "users.db :";
                                                echo '<br>';
                                            echo '</div>';

                                            echo '<form id="userform" method="post" action="?action=register" name="registerform">';

                                                echo '<table id="registrationtable">';

                                                    echo '<tbody id="registrationform">';

                                                        echo '<tr id="usernameinput">';
                                                            echo '<td><i class="fa fa-fw fa-user"> </i> <input id="login_input_username" type="text" pattern="[a-zA-Z0-9]{2,64}" name="user_name" placeholder=" Username" title="Enter a username" required autocomplete="off" /> </td>';
                                                            echo '<td><label for="login_input_username"><i> Letters and numbers only, 2 to 64 characters </i></label></td>';
                                                        echo '</tr>';

                                                        echo '<tr id="useremail">';
                                                            echo "<td><i class='fa fa-fw fa-envelope'> </i> <input id='login_input_email' type='email' name='user_email' placeholder=' User e-mail' /></td>";
                                                            echo '<td><label for="login_input_email"> <i> Not required </i></label></td>';
                                                        echo ' </tr>';

                                                        echo '<tr id="userpassword">';
                                                            echo "<td><i class='fa fa-fw fa-key'> </i> <input id='login_input_password_new' class='login_input' type='password' name='user_password_new' pattern='.{6,}' required autocomplete='off' placeholder=' Password' title='Enter a password' /></td>";
                                                            ?>
                                                            
                                                            <td><input id='login_input_password_repeat' class='login_input' type='password' name='user_password_repeat' pattern='.{6,}' fv-not-empty=' This field cannot be empty' fv-advanced='{"regex": "\\s", "regex_reverse": true, "message": "  Value cannot contain spaces"}' fv-valid-func='$( "#registerbtn" ).prop( "disabled", false )' fv-invalid-func='$( "#registerbtn" ).prop( "disabled", true )' required autocomplete='off' placeholder=' Repeat password' title='Repeat password' /><i> Minimum 6 characters </i></td>

                                                            <?php
                                                        
                                                        echo '</tr>';

                                                    echo ' </tbody>';

                                                echo '</table>';

                                                echo '<div id="loginerror">';

                                                    if ($this->feedbackerror) {
                                                        echo $this->feedbackerror;
                                                    };

                                                echo '</div>';

                                                echo '<input id="registerbtn" type="submit" class="btn btn-primary" name="register" value="Register" />';
                                                    echo '<br>';

                                                echo '<div id="loginsuccess">';

                                                    // $this->feedback = "This user does not exist.";

                                                    if ($this->feedbacksuccess) {
                                                        
                                                        echo '<div id="myModal" class="modalreg">';

                                                            echo '<div id="mymodal2" class="modal-content">';
                                                            
                                                                echo $this->feedbacksuccess;
                                                            
                                                            echo '</div>';
                                                            echo '<span class="close closereg"  aria-hidden="true" title="close">&times;</span>';

                                                         echo '</div>';
                                                    };

                                                echo '</div>';

                                            echo '</form>';

                                            echo ' <div id="loginerror">';
                                                echo '<i class="fa fa-fw fa-exclamation-triangle"> </i><b> NOTE: </b> <br> ';
                                            echo ' </div>';

                                            echo ' <div id="usernotes">';
                                                echo "<i> + It is NOT possible to change a user's credentials after creation. ";
                                                    echo '<br>';
                                                echo ' + If credentials need to be changed or reset, rename the file in your data directory '; echo $this->db_sqlite_path ;  echo ' to "users.old". Once that file is renamed, browse to this page again to recreate desired credentials. </i> ';
                                            echo ' </div>';

                                        }

                                        else {
                                        }

                                    ?>

                                </div>
                            <!--  END create user form -->

                            <!-- reginfo: -->
                                <?php

                                    echo '<div id="reginfo">';

                                            echo "<hr>";

                                        echo "Current working directiory: ";
                                            echo "<br>";

                                        echo getcwd() . "\n";
                                            echo "<br>";

                                            if (!is_dir($this->datadir)) {
                                                echo "Data directory NOT present.";
                                            }

                                            else {
                                                echo 'Data directory present:';
                                                    echo "<br>";
                                                echo $this->datadir;
                                            }

                                                echo "<br>";

                                            if (!is_file($this->db_sqlite_path)) {
                                                echo "Database file NOT present.";
                                                echo "<br><br>";
                                            }

                                            else {
                                                echo 'Database file present:';
                                                    echo "<br>";
                                                echo $this->db_sqlite_path;
                                                    echo "<br>";
                                            }

                                            echo "<br>";
                                    echo '</div>';

                                ?>
                            <!-- reginfo: -->


                            <div id="footer">
                                <p> <a class="footer a" href="https://github.com/monitorr/Monitorr" target="_blank"> Monitorr </a> | <a class="footer a" href="https://github.com/Monitorr/Monitorr/releases" target="_blank"> <?php echo file_get_contents('../../js/version/version.txt');?> </a> </p>
                            </div>

                        </body>

                    </html>

                <!--  END registration form -->

            <?php
        }


        /**
         * Simple demo-"page" with the login form.
         * In a real application you would probably include an html-template here, but for this extremely simple
         * demo the "echo" statements are totally okay.
         */
        // private function showPageLoginForm()
        // {
            // if ($this->feedback) {
            //     echo $this->feedback . "<br/><br/>";
            // }


        // }

        /**
         * Simple demo-"page" with the registration form.
         * In a real application you would probably include an html-template here, but for this extremely simple
         * demo the "echo" statements are totally okay.
         */

        private function showPageRegistration()

        {
            error_reporting(E_ALL);
?>
            
            <?php

                $datafile = '../../data/datadir.json';
                $str = file_get_contents($datafile);
                $json = json_decode( $str, true);
                $datadir1 = $json['datadir'];
                $dbfile = $datadir1 . 'users.db';


                 // IF users.db is NOT present, show registration page: //

                if (!is_file($dbfile)) {
            
                    $this->runregister();
                    
                }

                else {

                        $datafile = '../../data/datadir.json';
                        $str = file_get_contents($datafile);
                        $json = json_decode( $str, true);
                        $datadir = $json['datadir'];
                        $jsonfileuserdata = $datadir . 'user_preferences-data.json';

                        if(!is_file($jsonfileuserdata)){

                            $path = "../../../assets/";

                            include_once ('../monitorr-data-default.php');

                        }

                        else {

                            $datafile = '../../data/datadir.json';

                            include_once ('../monitorr-data.php');

                            $registration = $jsonusers['registration'];
                        }
                    
                    // IF users.db IS present, check if register setting is set to DISABLE, if YES, DENY access: //

                    if($registration == "Disable") {

                        // Deny access to register page if users.db is present AND register setting is set to DISABLE //

                            echo '<div class="wrapperregistrationdisable">';

                                echo '<div class="navbar-brand">';
                                    echo 'Monitorr';
                                echo '</div>';
                                    echo '<br><br>';

                                    echo "<font color='red'> You are NOT authorized to access this page. </font>";
                                        echo "<br><br>";
                                    echo "+  If you are the administrator and are trying to access the Monitorr registration tool, change the Monitorr User Preferences setting 'Registration Access' to 'Enable'. ";
                                        echo "<br>";
                                    echo "+  If you cannot access the Monitorr settings page, rename the file <i>"  . realpath($dbfile) .  " </i> and browse to this page again. ";
                                        echo "<br>";
                                    echo "+  <a href='../../../index.php' target='_blank'> Return to the Monitorr UI. </a>";

                            echo '</div>';

                            echo '<div id="footer">';

                                echo ' <a class="footer a" href="https://github.com/monitorr/Monitorr" target="_blank"> Monitorr </a> | <a class="footer a" href="https://github.com/Monitorr/Monitorr/releases" target="_blank"> </a>'; echo file_get_contents( "../../js/version/version.txt" ) ;

                            echo '</div>';

                    } 

                    else {

                         // Run registration access://

                        $this->runregister();
                       
                    }
                }
            ?>

<?php

        } //showPageRegistration

    } //OneFileLoginApplication

    // run the application:
    $application = new OneFileLoginApplication();

?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="UTF-8">
        <title>Monitorr | Registration</title>
        <link type="text/css" href="../../css/bootstrap.min.css" rel="stylesheet" />
        <link type="text/css" href="../../css/main.css" rel="stylesheet">
        <script src="../../js/jquery.min.js"></script>
        <script src="../../js/formValidation.js"></script>

        <style type="text/css">

            body {
                color: white;
                font-size: 1rem;
            }

            body::-webkit-scrollbar {
                width: .75rem;
                background-color: #252525;
            }

            body::-webkit-scrollbar-track {
                -webkit-box-shadow: inset 0 0 .25rem rgba(0, 0, 0, 0.3);
                box-shadow: inset 0 0 .25rem rgba(0, 0, 0, 0.3);
                border-radius: .75rem;
                background-color: #252525;
            }

            body::-webkit-scrollbar-thumb {
                border-radius: .75rem;
                -webkit-box-shadow: inset 0 0 .25rem rgba(0, 0, 0, .3);
                box-shadow: inset 0 0 .25rem rgba(0, 0, 0, .3);
                background-color: #8E8B8B;
            }

            .wrapper {
                margin-top: 5%;
                margin-left: auto;
                margin-right: auto;
                padding: 1rem;
            }

            #loginerror {
                font-size: 1rem;
                width: 75%;
            }

            .navbar-brand {
                cursor: default;
            }

            tbody {
                cursor: default;
            }

            input[type=text] {
                color: black !important;
                background: rgb(225, 225, 225) !important;
                border: 1px solid #ced4da;
                border-radius: .25rem;
                padding: .2rem;
            }

            input[type=text]:hover {
                color: black !important;
                background: white !important;
            }

            input[type=password] {
                color: black;
                background: rgb(225, 225, 225) !important;
                border: 1px solid #ced4da;
                border-radius: .25rem;
                padding: .2rem;
            }

            input[type=password]:hover {
                color: black !important;
                background: white !important;
            }

            input[type=email] {
                color: black;
                background: rgb(225, 225, 225) !important;
                border: 1px solid #ced4da;
                border-radius: .25rem;
                padding: .2rem;
            }

            input[type=email]:hover {
                color: black !important;
                background: white !important;
            }

        </style>

    </head>

</html>
