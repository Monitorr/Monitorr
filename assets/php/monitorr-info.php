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
   // private $db_sqlite_path = "../config/data/users.db"; 

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

            $str = file_get_contents( "../data/datadir.json" );

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
            require_once("../config/_installation/vendor/password_compatibility_library.php");
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
        // check is user wants to see register page (etc.)
        if (isset($_GET["action"]) && $_GET["action"] == "register") {
            $this->doRegistration();
            $this->showPageRegistration();
        } else {
            // start the session, always needed!
            $this->doStartSession();
            // check for possible user interactions (login with session/post data or logout)
            $this->performUserLoginAction();
            // show "page", according to user's login status
            if ($this->getUserLoginStatus()) {
                $this->showPageLoggedIn();
            } else {
                $this->showPageLoginForm();
            }
        }
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
        header("location: ../../settings.php");
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
                $this->feedback = "Invalid password";
            }
        } else {
            $this->feedback = "User does not exist";
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
            && !empty($_POST['user_email'])
            && strlen($_POST['user_email']) <= 64
            && filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)
            && !empty($_POST['user_password_new'])
            && strlen($_POST['user_password_new']) >= 6
            && !empty($_POST['user_password_repeat'])
            && ($_POST['user_password_new'] === $_POST['user_password_repeat'])
        ) {
            // only this case return true, only this case is valid
            return true;
        } elseif (empty($_POST['user_name'])) {
            $this->feedback = "Empty Username";
        } elseif (empty($_POST['user_password_new']) || empty($_POST['user_password_repeat'])) {
            $this->feedback = "Empty Password";
        } elseif ($_POST['user_password_new'] !== $_POST['user_password_repeat']) {
            $this->feedback = "Password and password repeat are not the same";
        } elseif (strlen($_POST['user_password_new']) < 6) {
            $this->feedback = "Password has a minimum length of 6 characters";
        } elseif (strlen($_POST['user_name']) > 64 || strlen($_POST['user_name']) < 2) {
            $this->feedback = "Username cannot be shorter than 2 or longer than 64 characters";
        } elseif (!preg_match('/^[a-z\d]{2,64}$/i', $_POST['user_name'])) {
            $this->feedback = "Username does not fit the name scheme: only a-Z and numbers are allowed, 2 to 64 characters";
        } elseif (empty($_POST['user_email'])) {
            $this->feedback = "Email cannot be empty";
        } elseif (strlen($_POST['user_email']) > 64) {
            $this->feedback = "Email cannot be longer than 64 characters";
        } elseif (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
            $this->feedback = "Your email address is not in a valid email format";
        } else {
            $this->feedback = "An unknown error occurred.";
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
                $this->feedback = "Your account has been created successfully. You can now log in.";
                return true;
            } else {
                $this->feedback = "Sorry, your registration failed. Please go back and try again.";
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

       
?>
<!DOCTYPE html>
<html lang="en">

    <head>
    
        <meta charset="utf-8">
        <link type="text/css" href="../css/bootstrap.min.css" rel="stylesheet">
        <link type="text/css" href="../css/main.css" rel="stylesheet">
        <link type="text/css" href="../data/css/custom.css" rel="stylesheet">

        <meta name="theme-color" content="#464646" />
        <meta name="theme_color" content="#464646" />

        <script type="text/javascript" src="../js/jquery.min.js"></script>
        <!-- <script type="text/javascript" src="../js/pace.js" async></script> -->

        <style>

            body {
                margin: 2vw !important;
                overflow-y: auto;
                overflow-x: hidden;
                background-color: #1F1F1F;
            }

            legend {
                color: white;
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
            
            #centertext {
                padding-bottom: 2rem !important;
            }


            #includedContent {
                float: right;
                width: 95% !important;
            }

            tbody {
                cursor: default !important;
            }

            .links {
                color: yellow !important;
                font-size: 1rem !important;
                font-weight: 500 !important;
            }

            select, input {
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
            }

        </style>

        <?php

            $file = '../data/datadir.json';

            if(!is_file($file)){

                $path = "../";

                include_once ('../config/monitorr-data-default.php');
            } 

            else {
                
                $datafile = '../data/datadir.json';

                include_once ('../config/monitorr-data.php');
            }
        ?> 

        <title>
            <?php
                $title = $jsonusers['sitetitle'];
                echo $title . PHP_EOL;
            ?>
            | Info
        </title>

        <!-- <?php include ('gitinfo.php'); ?> -->

    </head>

    <body>

        <script>
            document.body.className += ' fade-out';
            $(function() {
                $('body').removeClass('fade-out');
            });
        </script>

        <div id="centertext">
            <div class="navbar-brand settings-brand">
                Information
            </div>
        </div>

        <div id="infodata">

            <table class="table">
                <thead> <div id="blank"> . </div> </thead>

                <tbody>

                    <tr>
                        <td><strong>Monitorr Installed Version:</strong></td>
                        <td><?php echo file_get_contents( "../js/version/version.txt" )?> <p id="version_check_auto"></p> </td>
                        <td><strong>OS / Version:</strong></td>
                        <td><?php echo php_uname(); ?></td>
                    </tr>

                    <tr>
                        <td><strong>Monitorr Latest Version:</strong></td>
                        <td>Master: 
                            <a href="https://github.com/monitorr/monitorr/releases" target="_blank" title="Monitorr Releases">
                                <img src="https://img.shields.io/github/release/monitorr/monitorr.svg?style=flat" label="Monitorr Release" alt="Monitorr Release" style="width:6rem;height:1.1rem;" >
                            </a>
                            | Develop: 
                            <a href="https://github.com/monitorr/monitorr/releases" target="_blank" title="Monitorr Releases">
                                <img src="https://img.shields.io/github/release/monitorr/monitorr/all.svg" label="Monitorr Release" alt="Monitorr Release" style="width:6rem;height:1.1rem;" >
                            </a>
                        </td>

                        <td>
                            <strong>PHP Version:</strong>
                        </td>

                        <td>

                            <?php echo phpversion() ;

                                echo " <strong> | Extensions: </strong> ";
                         
                                if (extension_loaded('curl')) {
                                    echo " <div class='extok' title='PHP cURL extension loaded OK' >";
                                        echo "cURL";
                                    echo "</div>";
                                }

                                else {
                                    echo " | <a class='extfail' href='https://github.com/Monitorr/Monitorr/wiki/01-Config:--Initial-configuration' target='_blank' title='PHP cURL extension NOT loaded'>";
                                        echo "cURL";
                                    echo "</a>";
                                }

                                if (extension_loaded('sqlite3')) {
                                    echo " | <div class='extok' title='PHP sqlite3 extension loaded OK'>";
                                        echo "php_sqlite3";
                                    echo "</div>";
                                }

                                else {
                                    echo " | <a class='extfail' href='https://github.com/Monitorr/Monitorr/wiki/01-Config:--Initial-configuration' target='_blank' title='PHP php_sqlite3 extension NOT loaded'>";
                                        echo "php_sqlite3";
                                    echo "</a>";
                                }

                                if (extension_loaded('pdo_sqlite')) {
                                    echo " | <div class='extok' title='PHP pdo_sqlite extension loaded OK'>";
                                        echo "pdo_sqlite";
                                    echo "</div>";
                                }

                                else {
                                    echo " | <a class='extfail' href='https://github.com/Monitorr/Monitorr/wiki/01-Config:--Initial-configuration' target='_blank' title='PHP pdo_sqlite extension NOT loaded'>";
                                        echo "pdo_sqlite";
                                    echo "</a>";
                                }

                                if (extension_loaded('zip')) {
                                    echo " | <div class='extok' title='PHP ZIP extension loaded OK'>";
                                        echo "php7-zip";
                                    echo "</div>";
                                }

                                else {
                                    echo " | <a class='extfail' href='https://github.com/Monitorr/Monitorr/wiki/01-Config:--Initial-configuration' target='_blank' title='php7-zip extension NOT loaded'>";
                                        echo "php7-zip";
                                    echo "</a>";
                                }

                            ?>
                        
                        </td>
                    </tr>

                    <tr> 
                        <td><strong>Check & Execute Update:</strong></td>
                        <td>
                            Update branch selected: 
                            <strong>
                            <?php
                                $updateBranch = $jsonusers['updateBranch'];
                                echo '| ' . $updateBranch . ' | ' . PHP_EOL;  
                            ?>
                            </strong>

                            <a id="version_check" class="btn" style="cursor: pointer" title="Execute Update">Check for Update</a>
                        </td>
                        <td><strong>Install Path: </strong></td>
                        <td>
                            <?php
                                $vnum_loc = "../../";
                                echo realpath($vnum_loc), PHP_EOL;
                            ?>

                            <strong>| User db path: </strong>

                            <?php
                                echo $this->db_sqlite_path
                            ?>

                        </td>
                    </tr>

                    <tr>
                        <td><strong>Tools:</strong></td>
                        <td>
                            <a href="../../index.min.php" class="toolslink" target="_blank" title="Monitorr Minimal"> Monitorr Minimal |</a>
                            <a href="../config/_installation/_register.php" class="toolslink" title="Monitorr Registration"> Registration |</a>
                            <a href="checkmanual.php" target="_blank" class="toolslink" title="Curl check tool"> Curl manual check |</a>
                            <a href="checkping.php" target="_blank" class="toolslink" title="Ping check tool"> Ping manual check  </a>
                        </td> 

                        <td><strong>Resources:</strong></td>
                        <td><a href="https://github.com/monitorr/Monitorr" target="_blank" title="Monitorr GitHub Repo"> <img src="https://img.shields.io/badge/GitHub-repo-green.svg" style="width:4rem;height:1rem;" alt="Monitorr GitHub Repo"></a> | <a href="https://hub.docker.com/r/monitorr/monitorr/" target="_blank" title="Monitorr Docker Repo"> <img src="https://img.shields.io/docker/build/monitorr/monitorr.svg?maxAge=2592000" style="width:6rem;height:1rem;" alt="Monitorr Docker Repo"></a> | <a href="https://feathub.com/Monitorr/Monitorr" target="_blank" title="Monitorr Feature Request"> <img src="https://img.shields.io/badge/FeatHub-suggest-blue.svg" style="width:5rem;height:1rem;" alt="Monitorr Feature Request"></a> | <a href="https://discord.gg/j2XGCtH" target="_blank" title="Monitorr Discord Channel"> <img src="https://img.shields.io/discord/102860784329052160.svg" style="width:5rem;height:1rem;" alt="Monitorr on Discord" ></a> | <a href="https://paypal.me/monitorrapp" target="_blank" title="Buy us a beer!"> <img src="https://img.shields.io/badge/Donate-PayPal-green.svg" style="width:4rem;height:1rem;" alt="PayPal" ></a> </td>
                    </tr>

                </tbody>
                    <tr>
                        <!-- <div id="blank"> . </div> -->
                    </tr>
            </table>

        </div>

        <div>
            <table id="infoframe">
                <tr>
                    <td class="frametd">
                        <div class="version">
                            <div id ="versioncontent"> </div>
                        </div>
                    </td>

                    <td class="frametd">
                        <div class="php">
                            <div id ="phpcontent"> </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <script>
            document.getElementById("versioncontent").innerHTML='<object id="versionobject" type="text/html" data="../../changelog.html" ></object>'
        </script>

        <script>
            document.getElementById("phpcontent").innerHTML='<object id="phpobject" type="text/html" data="phpinfo.php" ></object>' 
        </script>

        <script src="../js/update.js" async></script>
        <script src="../js/update_auto-settings.js" async></script>

        <div id="footer" class="settings-footer">

            <p> <a class="footer a" href="https://github.com/monitorr/Monitorr" target="_blank"> Monitorr </a> | <a class="footer a" href="https://github.com/Monitorr/Monitorr/releases" target="_blank"> <?php echo file_get_contents( "../js/version/version.txt" );?> </a> </p>

        </div>

    </body>

</html>
<?php


        //echo 'Hello ' . $_SESSION['user_name'] . ', you are logged in.<br/><br/>';
       // echo '<a href="' . $_SERVER['SCRIPT_NAME'] . '?action=logout">Log out</a>';
    }

    /**
     * Simple demo-"page" with the login form.
     * In a real application you would probably include an html-template here, but for this extremely simple
     * demo the "echo" statements are totally okay.
     */
    private function showPageLoginForm()
    {
        
        $datadir = $this->datadir;
        $dbfile = $this->db_sqlite_path;
        
        echo '<div class="wrapper">';

            echo '<div class="navbar-brand">';
                echo 'Monitorr | Login';
            echo '</div>';
                echo '<br><br>';

                //Check if user database is present if not output error below:
            
            if(!is_file($dbfile)){

                echo "<div id='loginerror'>";
                        echo "<br>";
                    echo "<i class='fa fa-fw fa-exclamation-triangle'> </i> Data directory or user database NOT detected.";
                        echo "<br><br>";
                echo "<div>";

                echo "<div id='loginmessage'>";

                    echo 'Browse to <a href="../config/_installation/_register.php"> Monitorr Registration </a> to create a data directory and/or user database. ';

                echo "</div>";
                
            } 

                //if user database is present, show log-in form:

            else {

                echo '<form method="post" action="" name="loginform">';
                    echo '<div id="username">';
                        echo '<label for="login_input_username"> </label> ';
                            echo '<br>';
                        echo '<i class="fa fa-fw fa-user"></i> <input id="login_input_username" type="text" pattern="^\S+$" placeholder="Username" name="user_name" autofocus required title="Enter your username" autocomplete="username" /> ';
                    echo '</div>';
                        
                    echo '<div id="password">';
                        echo '<label for="login_input_password"> </label> ';
                            echo '<br>';
                        echo '<i class="fa fa-fw fa-key"></i> <input id="login_input_password" type="password" placeholder="Password" name="user_password" required  title="Enter your password" autocomplete="current-password" /> ';
                            echo '<br><br>';
                    echo '</div>';

                    echo "<div id='loginerror'>";

                        if ($this->feedback) {
                            echo $this->feedback . "<br/> <br/>";  // Failed login notification //
                        }

                    echo "</div>";

                    echo '<div id="loginbtn">';
                        echo '<input type="submit" class="btn btn-primary" name="login" value="Log in" />';
                    echo "</div>";

                echo '</form>';
                    echo '<br><br>';

                echo "<div id='reginfo'>";
                    echo "User database dir: " .  $datadir;
                            echo '<br>';
                    echo "User database file: " . $dbfile;
                echo "</div>";
            } 

        echo '</div>';
        
    }

    /**
     * Simple demo-"page" with the registration form.
     * In a real application you would probably include an html-template here, but for this extremely simple
     * demo the "echo" statements are totally okay.
     */
    private function showPageRegistration()
    {
        if ($this->feedback) {
            echo $this->feedback . "<br/><br/>";
        }

        echo "<div id='loginerror'>";
            echo 'Not Authorized';
        echo "</div>";

    }
}

// run the application
$application = new OneFileLoginApplication();

?>
<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="UTF-8">
        <title>Monitorr | Login</title>
        <link type="text/css" href="../css/bootstrap.min.css" rel="stylesheet" />
        <link type="text/css" href="../css/main.css" rel="stylesheet">
        <link type="text/css" href="../data/css/custom.css" rel="stylesheet">
        <script src="../js/jquery.min.js"></script>
        <script type="text/javascript" src="../js/pace.js" async></script>

        <style type="text/css">

            body { 
                color: white;
                background-color: #1F1F1F;
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

            .navbar-brand { 
                cursor: default;
            }

            .wrapper { 
                width: 30rem;
                /* margin-top: 10%; */
                margin-left: auto;
                margin-right: auto;
                padding: 1rem; 
            }

            input[type=text] {
                width: 12rem;
                padding: .175rem .75rem;
                font-size: 1.2rem;
                line-height: 1.5;
                color: black;
                background: rgb(200, 200, 200);
                border: 1px solid #ced4da;
                border-radius: .25rem;
                transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
            }

            input[type=password] {
                width: 12rem;
                padding: .175rem .75rem;
                font-size: 1.2rem;
                line-height: 1.5;
                color: black;
                background: rgb(200, 200, 200);
                border: 1px solid #ced4da;
                border-radius: .25rem;
                transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
            }

            #loginbtn {
                padding-left: 1.8rem;
            }

        </style>
        
    </head>

    <body>

        <script>
            document.body.className += ' fade-out';
            $(function() { 
                $('body').removeClass('fade-out'); 
            });
        </script>

    </body>

</html>
