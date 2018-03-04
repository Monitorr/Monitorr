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
class OneFileLoginApplication
{
    /**
     * @var string Type of used database (currently only SQLite, but feel free to expand this with mysql etc)
     */
    private $db_type = "sqlite"; //

    /**
     * @var string Path of the database file (create this with _install.php)
     */
    private $db_sqlite_path = "../users.db";

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


    /**
     * Does necessary checks for PHP version and PHP password compatibility library and runs the application
     */
    public function __construct()
    {
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
            require_once("libraries/password_compatibility_library.php");
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
                $this->feedbacksuccess = 'Your account has been created successfully. <a href="../../php/monitorr-info.php">Log in here.</a> ';
                return true;
            } else {
                $this->feedbackerror = "Sorry, your registration failed. Please and try again.";
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

        function createdb() {
        
            error_reporting(E_ALL);

            // config
            $db_type = "sqlite";
            
            $db_sqlite_path = "../users.db";

            // create new database file / connection (the file will be automatically created the first time a connection is made up)
            $db_connection = new PDO($db_type . ':' . $db_sqlite_path);

            // create new empty table inside the database (if table does not already exist)
            $sql = 'CREATE TABLE IF NOT EXISTS `users` (
                    `user_id` INTEGER PRIMARY KEY,
                    `user_name` varchar(64),
                    `user_password_hash` varchar(255),
                    `user_email` varchar(64));
                    CREATE UNIQUE INDEX `user_name_UNIQUE` ON `users` (`user_name` ASC);
                    CREATE UNIQUE INDEX `user_email_UNIQUE` ON `users` (`user_email` ASC);
                    ';

                // execute the above query
                $query = $db_connection->prepare($sql);
                $query->execute();

        }

        // check for success:


            // if (!file_exists($db_sqlite_path)) {
            //     echo "<div id='loginerror'>";
            //         echo "<br><br>";
            //         echo "Database $db_sqlite_path was not created, installation was NOT successful. Missing folder write rights ?";
            //     echo "</div>";
            // } 
            
            
           // else {

                echo '<div class="wrapperregistration">';
                    echo '<div class="navbar-brand">';
                        echo 'Monitorr | Registration';
                    echo '</div>';
                    echo '<br><br>';
                
                    echo '<div id="loginmessage">';
                         echo 'Create user database:';
                     echo '</div>';

    ?>
                <!DOCTYPE html>
                <html lang="en">

                <head>

                    <meta charset="UTF-8">
                    <!-- <link type="text/css" href="../css/bootstrap.min.css" rel="stylesheet" /> -->
                    <!-- <link type="text/css" href="../css/main.css" rel="stylesheet"> -->
                    <!-- <script src="../js/jquery.min.js"></script> -->
                    <script src="../../js/jquery.min.js"></script>

                    <!-- <style type="text/css">

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

                    </style> -->

                    
                </head>

                    <body>

                        <script>
                            $(document).ready(function(){

                                $('#userForm').submit(function(){
                                
                                    $('#response').html("<b>Loading response...</b>");
                                    
                                    $.post({
                                        url: './mkdirajax.php',
                                        data: $(this).serialize(),
                                        success: function(data){
                                            alert("Directory Created successfully");
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

                            <br><br>

                        <?php 
                        
                       // $dbfile = $this->db_sqlite_path;  //change me
                        
                       // echo  $dbfile; //change me
                        
                        ?>

                        <form id='userForm'>
                            Desired Data Directory:
                                <br> <br>
                            <div>
                                <input type='text' name='datadir' placeholder='Data Dir Path' />
                            </div>

                                <br>

                            <div>
                                <input type='submit' class="btn btn-primary" value='Create' />
                            </div>

                        </form>

                        <br>

                        <?php

                            Echo "Current working directiory: ";
                                echo "<br>";
                                // current directory
                            echo getcwd() . "\n";

                        ?>

                            <br><br>

                           
                        <div id='response' class='dbmessagesuccess'></div>

                    </body>

                </html>

                           
                        <!--                               
                            //  echo ' $(document).ready(function(){ ';

                        //           echo ' $("#userForm").submit(function(){ ';
                                    
                        //              echo ' ("#response").html("<b>Loading response...</b>"); ';
                                        
                        //              echo ' $.post({ ';
                        //                 echo '  url: ""../../php/mkdirajax.php", ';
                        //                  echo ' data: $(this).serialize(), ';
                        //                  echo '     success: function(data){ ';
                        //                  echo '       alert("Directory Created successfully"); ';
                        //                  echo '       $("#response").html(data); ';
                        //                  echo '   } ';
                        //             echo '    }) ';

                        //            echo '  .fail(function() { ';
                        //             echo '      alert( "Posting failed." ); ';
                        //            echo '     }); ';

                        //                 echo ' return false; ';
                         //           echo ' }); ';
                             
                        
                        //  echo ' }); '; -->

                        
<!--                          <script>

                               // $(document).ready(function(){

                                    $('#userForm').submit(function(){
                                    
                                        $('#response').html("<b>Loading response...</b>");
                                        
                                        $.post({
                                            url: 'mkdirajax.php',
                                            data: $(this).serialize(),
                                            success: function(data){
                                                alert("Directory Created successfully");
                                                $('#response').html(data);
                                            }
                                        })

                                        .fail(function() {
                                            alert( "Posting failed." );
                                        });

                                        return false;
                                    });
                              //  });


                        </script> -->

                        <?php

                        //    echo ' <form id="userForm"> ';
                        //       echo ' Desired Data Directory: ';
                        //            echo ' <br> <br> ';
                        //        echo ' <div> ';
                        //             echo '<input type="text" name="datadir" placeholder="Data Dir Path" /> ';
                        //        echo ' </div> ';

                        //            echo ' <br> ';

                        //         echo '<div> ';
                        //            echo ' <input type="submit" class="btn btn-primary" value="Submit" /> ';
                        //        echo ' </div> ';

                        //    echo ' </form> ';

                        //         Echo "Current working directiory: ";
                        //             echo "<br>";

                        //         echo getcwd() . "\n";

                        //    echo ' <div id="response"></div> ';



                           //Create user:  ** CHANGE ME **

                    echo '<div id="loginsuccess">';
                       // echo "Database $db_sqlite_path was created, installation was successful.";
                        echo '<br><br>';
                    echo '</div>';


                     echo '<div id="loginmessage">';
                         echo 'Create New User:';
                         echo '<br><br>';
                     echo '</div>';

                    echo '<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '?action=register" name="registerform">';

                        echo '<table id="registrationtable">';
                            echo '<thead> <div id="blank"> . </div> </thead>';
                            echo '<tbody id="registrationform">';

                                echo '<tr>';
                                    echo '<td><input id="login_input_username" type="text" pattern="[a-zA-Z0-9]{2,64}" name="user_name" placeholder="Username" required /> </td>';
                                    echo '<td><label for="login_input_username"><i> letters and numbers only, 2 to 64 characters </i></label></td>';
                                    
                                echo '</tr>';

                                echo '<tr>';
                                   echo "<td><input id='login_input_email' type='email' name='user_email' placeholder='User e-mail' /></td>";
                                    echo '<td><label for="login_input_email"> <i> Not required </i></label></td>';
                                
                               echo ' </tr>';

                               echo ' <tr>';
                                   echo " <td><input id='login_input_password_new' class='login_input' type='password' name='user_password_new' pattern='.{6,}' required autocomplete='off' placeholder='Password' /></td>";
                                   echo ' <td><label for="login_input_password_new"> <i>Minimum 6 characters </i></label></td>';
                                
                               echo ' </tr>';

                               echo ' <tr>';
                                    echo '<td><input id="login_input_password_repeat" class="login_input" type="password" name="user_password_repeat" pattern=".{6,}" required autocomplete="off" placeholder="Repeat password" /></td>';
                                   echo ' <td><label for="login_input_password_repeat"> </label></td>';
                                    
                                echo '</tr>';
                                
                           echo ' </tbody>';
                            echo '<tr>';

                           echo ' </tr>';
                       echo ' </table>';

                        echo ' <div id="loginsuccess"><strong>';
                            if ($this->feedbacksuccess) {
                                echo $this->feedbacksuccess . "<br/><br/>";
                            }
                        echo ' </strong></div>';

                        echo ' <div id="loginerror"><strong>';
                            if ($this->feedbackerror) {
                                echo $this->feedbackerror . "<br/><br/>";
                            }
                        echo ' </strong></div>';

                         echo ' <input type="submit" class="btn btn-primary" name="register" value="Register" />';

                            echo '  <br><br>';

                         echo ' <div id="loginerror">';
                         echo ' <b>NOTE:</b> <br> ';
                         echo " It is NOT possible to change a user's credentials after creation. If you want to change or reset credentials, rename the file '/monitorr/assets/data/users.db' to 'users.old'. Once that file is renamed, browse to this page again to recreate desired credentials. ";
                         echo ' </div>';

                    echo '</form>';

                    // echo' <!-- <a href="' . $_SERVER['SCRIPT_NAME'] . '">Homepage</a> -->';

                echo '  </div>';

                 echo ' <div id="footer">';
                   echo ' <p> <a class="footer a" href="https://github.com/monitorr/Monitorr" target="_blank"> Monitorr </a> | <a class="footer a" href="https://github.com/Monitorr/Monitorr/releases" target="_blank">'; echo file_get_contents('../../js/version/version.txt'); echo '</a> </p>';
                 echo ' </div>';


           // }

    }
}

// run the application
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

    <style type="text/css">

        body { 
            color: white;
            font-size: 18px
        }

/*         :root {
            font-size: 18px !important;
        } */

        .wrapper { 
            /* width: 30rem; */
            margin-top: 5%;
            margin-left: auto;
            margin-right: auto;
            padding: 1rem; 
        }

        #loginerror {

            font-size: 1rem;
            width: 75%;
        }

    </style>

    
</head>

</html>