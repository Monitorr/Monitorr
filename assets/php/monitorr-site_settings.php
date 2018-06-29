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
    //private $db_sqlite_path = "../data/users.db";

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
        <link type="text/css" href="../css/alpaca.min.css" rel="stylesheet">
        <!-- <link type="text/css" href="../css/main.css" rel="stylesheet"> -->
        <link type="text/css" href="../data/css/custom.css" rel="stylesheet">

        <meta name="theme-color" content="#464646" />
        <meta name="theme_color" content="#464646" />

        <script type="text/javascript" src="../js/jquery.min.js"></script>
        <!-- <script type="text/javascript" src="../js/pace.js" async></script> -->
        <script type="text/javascript" src="../js/handlebars.js"></script>
        <script type="text/javascript" src="../js/bootstrap.min.js"></script>
        <script type="text/javascript" src="../js/alpaca.min.js"></script>
        
            <style>

                body {
                    margin: 2vw !important;
                    overflow-y: auto;
                    overflow-x: hidden;
                    color: white !important;
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

                label {
                    width: 100% !important;
                    max-width: 100% !important;
                }

                .form-group {
                    margin-bottom: 2rem !important;
                }
                
                .alpaca-form-buttons-container {
                    position: absolute;
                    bottom: 15%;
                }

            </style>

            <?php $datafile = '../data/datadir.json'; ?>
            <?php include_once ('../config/monitorr-data.php')?>

            <?php  include ('functions.php'); ?>

            <title>
                <?php
                    $title = $jsonusers['sitetitle'];
                    echo $title . PHP_EOL;
                ?>
                | Settings
            </title>

    </head>

    <body>

        <script>
            document.body.className += ' fade-out';
            $(function() {
                $('body').removeClass('fade-out');
            });
        </script>

        <p id="response"></p>

        <div id="centertext">
            <div class="navbar-brand settings-brand">
                Monitorr Settings
            </div>
        </div>

        <div id="siteform">

            <table id="sitepreview">
                <tr>

                    <td id="previewleft">

                        <div id="ping" class="col-md-2 col-centered double-val-label">
                            <span class="<?php echo $pingclass; ?>">ping</span>
                            <span><?php echo $pingTime ;?> ms</span>
                        </div>

                    </td>

                    <td id="previewcenter">
                        <div id="hd" class="col-md-2 col-centered double-val-label">
                        
                            <?php

                                if($disk1 == "") {
                                }

                                else {
                                
                                    echo "<span id='hdlabel1' class='" . $hdClass1 . "'> HD " .  $disk1 . " </span>";
                                    echo "<span id='hdpercent1' >" . $freeHD1 . "%</span>";
                                }

                                if($disk2 == "") {
                                }

                                else {
                                    echo "<span id='hdlabel2' class='" . $hdClass2 . " hdhidden'> HD " .  $disk2 . " </span>";
                                    echo "<span id='hdpercent2' class='hdhidden'>" . $freeHD2 . "%</span>";
                                }

                                if($disk3 == "") {
                                }

                                else {
                                    echo "<span id='hdlabel3' class='" . $hdClass3 . " hdhidden'> HD " .  $disk3 . " </span>";
                                    echo "<span id='hdpercent3' class='hdhidden'>" . $freeHD3 . "%</span>";
                                }

                            ?>

                        </div>
                    </td>

                    <td id="previewright">
                        <div id="systempreview">
                            <table id="systempreview">
                                <tr>
                                    <td>
                                        <div id="cpu" class="col-md-2 col-centered double-val-label">
                                            <span class="<?php echo $cpuClass; ?>">CPU</span>
                                            <span><?php echo $cpuPercent; ?>%</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div id="ram" class="col-md-2 col-centered double-val-label">
                                            <span class="<?php echo $ramClass; ?>">RAM</span>
                                            <span><?php echo $ramPercent; ?>%</span>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>

                </tr>
            </table>

            <div id="sitesettings"></div>

            <script type="text/javascript">
                $(document).ready(function() {
                    var CustomConnector = Alpaca.Connector.extend({
                        buildAjaxConfig: function(uri, isJson) {
                            var ajaxConfig = this.base(uri, isJson);
                            ajaxConfig.headers = {
                                "ssoheader": "abcde12345"
                            };
                            return ajaxConfig;
                        }
                    });

                    var data;
                    $.ajax({
                        dataType: "json",
                        url: './post_receiver-site_settings_load.php',
                        data: data,
                        success: function (data) {
                            console.log(data);
                        },

                        error: function(errorThrown){
                            console.log(errorThrown);
                            document.getElementById("response").innerHTML = "GET failed (ajax)";
                            alert( "GET failed (ajax)" );
                        },
                    });

                    Alpaca.registerConnectorClass("custom", CustomConnector);
                    $("#sitesettings").alpaca({
                        "connector": "custom",
                        "dataSource": "./post_receiver-site_settings_load.php",
                        "schemaSource": "../config/site_settings-schema.json?a=1",
                        "view": {
                            "parent": "bootstrap-edit-horizontal",
                            "layout": {
                                "template": '../css/./two-column-layout-template.html',
                                "bindings": {
                                    "rfsysinfo": "leftcolumn",
                                    "rftime": "leftcolumn",
                                    "pinghost": "leftcolumn",
                                    "pingport": "leftcolumn",
                                    "disk1enable": "tdcenterleft",
                                    "disk1": "disk1",
                                    "disk2enable": "tdcenterleft",
                                    "disk2": "disk2",
                                    "disk3enable": "tdcenterleft",
                                    "disk3": "disk3",
                                    "hdok": "centerbottom",
                                    "hdwarn": "centerbottom",
                                    "cpuok": "cpuok",
                                    "cpuwarn": "cpuwarn",
                                    "ramok": "ramok",
                                    "ramwarn": "ramwarn",
                                    "pingok": "pingok",
                                    "pingwarn": "pingwarn"
                                }
                            },
                            "fields": {
                                "/rfsysinfo": {
                                    "templates": {
                                        "control": "../css/forms/./templates-site-settings_rfsysinfo.html"
                                    },
                                    "bindings": {
                                        "rfsysinfo": "#rfsysinfo_input"
                                    }
                                },
                                "/rftime": {
                                    "templates": {
                                        "control": "../css/forms/./templates-site-settings_rftime.html"
                                    },
                                    "bindings": {
                                        "rftime": "#rftime_input"
                                    }
                                },
                                "/pinghost": {
                                    "templates": {
                                        "control": "../css/forms/./templates-site-settings_pinghost.html"
                                    },
                                    "bindings": {
                                        "pinghost": "#pinghost_input"
                                    }
                                },
                                "/pingport": {
                                    "templates": {
                                        "control": "../css/forms/./templates-site-settings_pingport.html"
                                    },
                                    "bindings": {
                                        "pingport": "#pingport_input"
                                    }
                                },
                                "/disk1enable": {
                                    "templates": {
                                        "control": "../css/forms/./templates-site-settings_disk1enable.html"
                                    },
                                    "bindings": {
                                        "disk1enable": "#disk1enable_select"
                                    }
                                },
                                "/disk1": {
                                    "templates": {
                                        "control": "../css/forms/./templates-site-settings_disk1.html"
                                    },
                                    "bindings": {
                                        "disk1": "#disk1_input"
                                    }
                                },
                                "/hdok": {
                                    "templates": {
                                        "control": "../css/forms/./templates-site-settings_hdok.html"
                                    },
                                    "bindings": {
                                        "hdok": "#hdok_input"
                                    }
                                },
                                "/hdwarn": {
                                    "templates": {
                                        "control": "../css/forms/./templates-site-settings_hdwarn.html"
                                    },
                                    "bindings": {
                                        "hdwarn": "#hdwarn_input"
                                    }
                                },
                                "/cpuok": {
                                    "templates": {
                                        "control": "../css/forms/./templates-site-settings_cpuok.html"
                                    },
                                    "bindings": {
                                        "cpuok": "#cpuok_input"
                                    }
                                },
                                "/cpuwarn": {
                                    "templates": {
                                        "control": "../css/forms/./templates-site-settings_cpuwarn.html"
                                    },
                                    "bindings": {
                                        "cpuwarn": "#cpuwarn_input"
                                    }
                                },
                                "/ramok": {
                                    "templates": {
                                        "control": "../css/forms/./templates-site-settings_ramok.html"
                                    },
                                    "bindings": {
                                        "ramok": "#ramok_input"
                                    }
                                },
                                "/ramwarn": {
                                    "templates": {
                                        "control": "../css/forms/./templates-site-settings_ramwarn.html"
                                    },
                                    "bindings": {
                                        "ramwarn": "#ramwarn_input"
                                    }
                                },
                                "/pingok": {
                                    "templates": {
                                        "control": "../css/forms/./templates-site-settings_pingok.html"
                                    },
                                    "bindings": {
                                        "pingok": "#pingok_input"
                                    }
                                },
                                "/pingwarn": {
                                    "templates": {
                                        "control": "../css/forms/./templates-site-settings_pingwarn.html"
                                    },
                                    "bindings": {
                                        "pingwarn": "#pingwarn_input"
                                    }
                                }
                            }
                        },
                        "options": {
                            "focus": false,
                            "type": "object",
                            "helpers": [],
                            "validate": true,
                            "disabled": false,
                            "showMessages": true,
                            "collapsible": false,
                            "legendStyle": "button",
                            "fields": {
                                "rfsysinfo": {
                                    "type": "number",
                                    "validate": true,
                                    "showMessages": true,
                                    "disabled": false,
                                    "hidden": false,
                                    "label": "Service & system refresh interval:",
                                    "helper": "Service & system info refresh interval in milliseconds.",
                                    "hideInitValidationError": false,
                                    "focus": false,
                                    "optionLabels": [],
                                    "name": "rfsysinfo",
                                    "placeholder": "5000",
                                    "typeahead": {},
                                    "size": "10",
                                    "allowOptionalEmpty": false,
                                    "data": {},
                                    "autocomplete": false,
                                    "disallowEmptySpaces": true,
                                    "disallowOnlyEmptySpaces": false,
                                    "fields": {},
                                    "renderButtons": true,
                                    "attributes": {},
                                    "events": {
                                        "change": function() {
                                            $('.alpaca-form-button-submit').addClass('buttonchange');
                                        }
                                    }
                                },
                                "rftime": {
                                    "type": "number",
                                    "validate": true,
                                    "showMessages": true,
                                    "disabled": false,
                                    "hidden": false,
                                    "label": "Time sync interval:",
                                    "helper": "Specifies how frequently (in milliseconds) the UI clock will synchronize time with the hosting webserver.",
                                    "hideInitValidationError": false,
                                    "focus": false,
                                    "optionLabels": [],
                                    "name": "rftime",
                                    "placeholder": "180000",
                                    "typeahead": {},
                                    "size": "10",
                                    "allowOptionalEmpty": false,
                                    "data": {},
                                    "autocomplete": false,
                                    "disallowEmptySpaces": true,
                                    "disallowOnlyEmptySpaces": false,
                                    "fields": {},
                                    "renderButtons": true,
                                    "attributes": {},
                                    "events": {
                                        "change": function() {
                                            $('.alpaca-form-button-submit').addClass('buttonchange');
                                        }
                                    }
                                },
                                "pinghost": {
                                    "type": "text",
                                    "validate": true,
                                    "showMessages": true,
                                    "disabled": false,
                                    "hidden": false,
                                    "label": "Ping host:",
                                    "size": 35,
                                    "helper": "URL or IP to ping for latency check. <br> (WAN DNS provider is suggested)",
                                    "hideInitValidationError": false,
                                    "focus": false,
                                    "optionLabels": [],
                                    "name": "pinghost",
                                    "placeholder": "8.8.8.8",
                                    "typeahead": {},
                                    "size": "10",
                                    "allowOptionalEmpty": false,
                                    "data": {},
                                    "autocomplete": false,
                                    "disallowEmptySpaces": true,
                                    "disallowOnlyEmptySpaces": false,
                                    "fields": {},
                                    "renderButtons": true,
                                    "attributes": {},
                                    "events": {
                                        "change": function() {
                                            $('.alpaca-form-button-submit').addClass('buttonchange');
                                        }
                                    }
                                },
                                "pingport": {
                                    "type": "number",
                                    "validate": true,
                                    "showMessages": true,
                                    "disabled": false,
                                    "hidden": false,
                                    "label": "Ping host port:",
                                    "helper": "Ping host port for ping latency check. <br> (If using 8.8.8.8, value should be '53')",
                                    "hideInitValidationError": false,
                                    "focus": false,
                                    "optionLabels": [],
                                    "name": "pingport",
                                    "placeholder": "53",
                                    "typeahead": {},
                                    "size": "10",
                                    "allowOptionalEmpty": false,
                                    "data": {},
                                    "autocomplete": false,
                                    "disallowEmptySpaces": true,
                                    "disallowOnlyEmptySpaces": false,
                                    "fields": {},
                                    "renderButtons": true,
                                    "attributes": {},
                                    "events": {
                                        "change": function() {
                                            $('.alpaca-form-button-submit').addClass('buttonchange');
                                        }
                                    }
                                },
                                "disk1enable": {
                                    "type": "select",
                                    "validate": true,
                                    "showMessages": true,
                                    "disabled": false,
                                    "hidden": false,
                                    "label": "HD1 display:",
                                    "hideInitValidationError": false,
                                    "focus": false,
                                    "name": "disk1enable",
                                    "typeahead": {},
                                    "allowOptionalEmpty": false,
                                    "data": {},
                                    "autocomplete": false,
                                    "disallowEmptySpaces": false,
                                    "disallowOnlyEmptySpaces": false,
                                    "removeDefaultNone": true,
                                    "fields": {},
                                    "events": {
                                        "change": function() {
                                            $('.alpaca-form-button-submit').addClass('buttonchange');
                                        }
                                    }
                                },
                                "disk1": {
                                    "dependencies": {
                                        "disk1enable": ["Enable"]
                                    },
                                    "type": "text",
                                    "validate": true,
                                    "showMessages": true,
                                    "disabled": false,
                                    "hidden": false,
                                    "label": "HD1 volume:",
                                    "hideInitValidationError": false,
                                    "focus": false,
                                    "optionLabels": [],
                                    "name": "disk1",
                                    "placeholder": "HD volume",
                                    "typeahead": {},
                                    "size": "7",
                                    "allowOptionalEmpty": false,
                                    "data": {},
                                    "autocomplete": false,
                                    "disallowEmptySpaces": true,
                                    "disallowOnlyEmptySpaces": true,
                                    "fields": {},
                                    "renderButtons": true,
                                    "attributes": {},
                                    "events": {
                                        "change": function() {
                                            $('.alpaca-form-button-submit').addClass('buttonchange');
                                        }
                                    }
                                },
                                "disk2enable": {
                                    "dependencies": {
                                        "disk1enable": ["Enable"]
                                    },
                                    "type": "select",
                                    "validate": true,
                                    "showMessages": true,
                                    "disabled": false,
                                    "hidden": false,
                                    "label": "HD2 display:",
                                    "hideInitValidationError": false,
                                    "focus": false,
                                    "name": "disk2enable",
                                    "typeahead": {},
                                    "allowOptionalEmpty": false,
                                    "data": {},
                                    "autocomplete": false,
                                    "disallowEmptySpaces": true,
                                    "disallowOnlyEmptySpaces": false,
                                    "removeDefaultNone": true,
                                    "fields": {},
                                    "events": {
                                        "change": function() {
                                            $('.alpaca-form-button-submit').addClass('buttonchange');
                                        }
                                    }
                                },
                                "disk2": {
                                    "dependencies": {
                                        "disk2enable": ["Enable"]
                                    },
                                    "type": "text",
                                    "validate": true,
                                    "showMessages": true,
                                    "disabled": false,
                                    "hidden": false,
                                    "label": "HD2 volume:",
                                    "hideInitValidationError": false,
                                    "focus": false,
                                    "optionLabels": [],
                                    "name": "disk2",
                                    "placeholder": "HD volume",
                                    "typeahead": {},
                                    "size": "7",
                                    "allowOptionalEmpty": true,
                                    "data": {},
                                    "autocomplete": false,
                                    "disallowEmptySpaces": true,
                                    "disallowOnlyEmptySpaces": false,
                                    "fields": {},
                                    "renderButtons": true,
                                    "attributes": {},
                                    "events": {
                                        "change": function() {
                                            $('.alpaca-form-button-submit').addClass('buttonchange');
                                        }
                                    }
                                },
                                "disk3enable": {
                                    "dependencies": {
                                        "disk2enable": ["Enable"]
                                    },
                                    "type": "select",
                                    "validate": true,
                                    "showMessages": true,
                                    "disabled": false,
                                    "hidden": false,
                                    "label": "HD3 display:",
                                    "hideInitValidationError": false,
                                    "focus": false,
                                    "name": "disk3enable",
                                    "typeahead": {},
                                    "allowOptionalEmpty": false,
                                    "data": {},
                                    "autocomplete": false,
                                    "disallowEmptySpaces": true,
                                    "disallowOnlyEmptySpaces": false,
                                    "removeDefaultNone": true,
                                    "fields": {},
                                    "events": {
                                        "change": function() {
                                            $('.alpaca-form-button-submit').addClass('buttonchange');
                                        }
                                    }
                                },
                                "disk3": {
                                    "dependencies": {
                                        "disk3enable": ["Enable"]
                                    },
                                    "type": "text",
                                    "validate": true,
                                    "showMessages": true,
                                    "disabled": false,
                                    "hidden": false,
                                    "label": "HD3 volume:",
                                    "hideInitValidationError": false,
                                    "focus": false,
                                    "optionLabels": [],
                                    "name": "disk3",
                                    "placeholder": "HD volume",
                                    "typeahead": {},
                                    "size": "7",
                                    "allowOptionalEmpty": true,
                                    "data": {},
                                    "autocomplete": false,
                                    "disallowEmptySpaces": true,
                                    "disallowOnlyEmptySpaces": false,
                                    "fields": {},
                                    "renderButtons": true,
                                    "attributes": {},
                                    "events": {
                                        "change": function() {
                                            $('.alpaca-form-button-submit').addClass('buttonchange');
                                        }
                                    }
                                },
                                "hdok": {
                                    "type": "number",
                                    "validate": true,
                                    "showMessages": true,
                                    "disabled": false,
                                    "hidden": false,
                                    "label": "HD OK color value:",
                                    "helper": "HD used % less than this value will be green.",
                                    "hideInitValidationError": false,
                                    "focus": false,
                                    "optionLabels": [],
                                    "name": "hdok",
                                    "placeholder": "75",
                                    "typeahead": {},
                                    "size": 5,
                                    "allowOptionalEmpty": false,
                                    "data": {},
                                    "autocomplete": false,
                                    "disallowEmptySpaces": true,
                                    "disallowOnlyEmptySpaces": false,
                                    "fields": {},
                                    "renderButtons": true,
                                    "attributes": {},
                                    "events": {
                                        "change": function() {
                                            $('.alpaca-form-button-submit').addClass('buttonchange');
                                        }
                                    }
                                },
                                "hdwarn": {
                                    "type": "number",
                                    "validate": true,
                                    "showMessages": true,
                                    "disabled": false,
                                    "hidden": false,
                                    "label": "HD warning color value:",
                                    "helper": "HD free % less than this will be yellow.",
                                    "hideInitValidationError": false,
                                    "focus": false,
                                    "optionLabels": [],
                                    "name": "hdwarn",
                                    "placeholder": "95",
                                    "typeahead": {},
                                    "size": 5,
                                    "allowOptionalEmpty": false,
                                    "data": {},
                                    "autocomplete": false,
                                    "disallowEmptySpaces": true,
                                    "disallowOnlyEmptySpaces": false,
                                    "fields": {},
                                    "renderButtons": true,
                                    "attributes": {},
                                    "events": {
                                        "change": function() {
                                            $('.alpaca-form-button-submit').addClass('buttonchange');
                                        }
                                    }
                                },
                                "cpuok": {
                                    "type": "number",
                                    "validate": true,
                                    "showMessages": true,
                                    "disabled": false,
                                    "hidden": false,
                                    "label": "CPU OK:",
                                    "helper": "CPU usage % less than this value will appear green, above this value will appear yellow.",
                                    "hideInitValidationError": false,
                                    "focus": false,
                                    "optionLabels": [],
                                    "name": "cpuok",
                                    "placeholder": "50",
                                    "typeahead": {},
                                    "size": 5,
                                    "allowOptionalEmpty": false,
                                    "data": {},
                                    "autocomplete": false,
                                    "disallowEmptySpaces": true,
                                    "disallowOnlyEmptySpaces": false,
                                    "fields": {},
                                    "renderButtons": true,
                                    "attributes": {},
                                    "events": {
                                        "change": function() {
                                            $('.alpaca-form-button-submit').addClass('buttonchange');
                                        }
                                    }
                                },
                                "cpuwarn": {
                                    "type": "number",
                                    "validate": true,
                                    "showMessages": true,
                                    "disabled": false,
                                    "hidden": false,
                                    "label": "CPU warning:",
                                    "helper": "CPU usage % less than this value will appear yellow, above this value will appear red",
                                    "hideInitValidationError": false,
                                    "focus": false,
                                    "optionLabels": [],
                                    "name": "cpuwarn",
                                    "placeholder": "90",
                                    "typeahead": {},
                                    "size": 5,
                                    "allowOptionalEmpty": false,
                                    "data": {},
                                    "autocomplete": false,
                                    "disallowEmptySpaces": true,
                                    "disallowOnlyEmptySpaces": false,
                                    "fields": {},
                                    "renderButtons": true,
                                    "attributes": {},
                                    "events": {
                                        "change": function() {
                                            $('.alpaca-form-button-submit').addClass('buttonchange');
                                        }
                                    }
                                },
                                "ramok": {
                                    "type": "number",
                                    "validate": true,
                                    "showMessages": true,
                                    "disabled": false,
                                    "hidden": false,
                                    "label": "RAM OK:",
                                    "helper": "RAM usage % less than this value will appear green, above this value will appear yellow.",
                                    "hideInitValidationError": false,
                                    "focus": false,
                                    "optionLabels": [],
                                    "name": "ramok",
                                    "placeholder": "50",
                                    "typeahead": {},
                                    "size": 5,
                                    "allowOptionalEmpty": false,
                                    "data": {},
                                    "autocomplete": false,
                                    "disallowEmptySpaces": true,
                                    "disallowOnlyEmptySpaces": false,
                                    "fields": {},
                                    "renderButtons": true,
                                    "attributes": {},
                                    "events": {
                                        "change": function() {
                                            $('.alpaca-form-button-submit').addClass('buttonchange');
                                        }
                                    }
                                },
                                "ramwarn": {
                                    "type": "number",
                                    "validate": true,
                                    "showMessages": true,
                                    "disabled": false,
                                    "hidden": false,
                                    "label": "RAM warning:",
                                    "helper": "RAM usage % less than this value will appear yellow, above this value will appear red.",
                                    "hideInitValidationError": false,
                                    "focus": false,
                                    "optionLabels": [],
                                    "name": "ramwarn",
                                    "placeholder": "90",
                                    "typeahead": {},
                                    "size": 5,
                                    "allowOptionalEmpty": false,
                                    "data": {},
                                    "autocomplete": false,
                                    "disallowEmptySpaces": true,
                                    "disallowOnlyEmptySpaces": false,
                                    "fields": {},
                                    "renderButtons": true,
                                    "attributes": {},
                                    "events": {
                                        "change": function() {
                                            $('.alpaca-form-button-submit').addClass('buttonchange');
                                        }
                                    }
                                },
                                "pingok": {
                                    "type": "number",
                                    "validate": true,
                                    "showMessages": true,
                                    "disabled": false,
                                    "hidden": false,
                                    "label": "Ping OK:",
                                    "helper": "Ping RT response time in ms less than this value will appear green, above this value will appear yellow.",
                                    "hideInitValidationError": false,
                                    "focus": false,
                                    "optionLabels": [],
                                    "name": "pingok",
                                    "placeholder": "50",
                                    "typeahead": {},
                                    "size": 5,
                                    "allowOptionalEmpty": false,
                                    "data": {},
                                    "autocomplete": false,
                                    "disallowEmptySpaces": true,
                                    "disallowOnlyEmptySpaces": false,
                                    "fields": {},
                                    "renderButtons": true,
                                    "attributes": {},
                                    "events": {
                                        "change": function() {
                                            $('.alpaca-form-button-submit').addClass('buttonchange');
                                        }
                                    }
                                },
                                "pingwarn": {
                                    "type": "number",
                                    "validate": true,
                                    "showMessages": true,
                                    "disabled": false,
                                    "hidden": false,
                                    "label": "Ping warning:",
                                    "helper": "Ping RT response time in ms less than this value will appear yellow, above this value will appear red.",
                                    "hideInitValidationError": false,
                                    "focus": false,
                                    "optionLabels": [],
                                    "name": "pingwarn",
                                    "placeholder": "100",
                                    "typeahead": {},
                                    "size": 5,
                                    "allowOptionalEmpty": false,
                                    "data": {},
                                    "autocomplete": false,
                                    "disallowEmptySpaces": true,
                                    "disallowOnlyEmptySpaces": false,
                                    "fields": {},
                                    "renderButtons": true,
                                    "attributes": {},
                                    "events": {
                                        "change": function() {
                                            $('.alpaca-form-button-submit').addClass('buttonchange');
                                        }
                                    }
                                }
                            },
                            "form": {
                                // "attributes": {
                                //     "action": "post_receiver-site_settings.php",
                                //     "method": "post",
                                // },
                                "buttons": {
                                    "submit": {
                                        "type": "button",
                                        "label": "Submit",
                                        "name": "submit",
                                        "value": "submit",
                                        click: function(){
                                            var data = $('#sitesettings').alpaca().getValue();
                                            $.post({
                                                url: 'post_receiver-site_settings.php',
                                                data: $('#sitesettings').alpaca().getValue(),
                                                success: function(data) {
                                                    console.log("POST: Settings saved!");
                                                    alert("Settings saved! Applying changes...");
                                                    $('.alpaca-form-button-submit').removeClass('buttonchange');
                                                    $('#sitepreview').load(document.URL + ' #sitepreview');
                                                    $('#colortable').load(document.URL + ' #colortable');                                                        
                                                },
                                                error: function(errorThrown){
                                                    console.log(errorThrown);
                                                    alert("POST Error: submitting data.");
                                                }
                                            });
                                        }
                                    },
                                    "reset":{
                                        "label": "Clear Values"
                                    }
                                    // "view": {
                                    //     "type": "button",
                                    //     "label": "View JSON",
                                    //     "value": "View JSON",
                                    //     "click": function() {
                                    //         alert(JSON.stringify(this.getValue(), null, "  "));
                                    //     }
                                    // }
                                },
                            }
                        },
                        "postRender": function(control) {
                            if (control.form) {
                                control.form.registerSubmitHandler(function (e) {
                                    control.form.getButtonEl('submit').click();
                                    return false;
                                });
                            }
                        }, 
                    });
                });
            </script>

        </div>

        <div id="colortable">
            <table id="colortable2">

                <tr>
                    <td id="colorkey" colspan="2">
                        Color value proportion key: <i class="fa fa-fw fa-question-circle input_icon" id="colorkey_icon" title="Represents your color values on a proportionate scale."> </i>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div id="hd" class="col-md-2 col-centered double-val-label">
                            <span class="<?php echo $hdClass1; ?> barlabel"> HD: </span>
                        </div>
                    </td>
                    <td id="hdbar1">
                        <div id="hdbar1"> 

                            <table id="hdbar2" class='colorbar'>
                                <tr style='width: 100%;'> 
                                    <td title="HD usage OK: 0% - <?php echo $jsonsite['hdok']; ?>%" style='background-color: #5cb85c; width: <?php echo $jsonsite['hdok']; ?>%;'> </td>
                                    <td title="HD usage warn: <?php echo $jsonsite['hdok']; ?>% - <?php echo $jsonsite['hdwarn']; ?>%" style='background-color: #f0ad4e; width:<?php echo(100 - $jsonsite['hdok'] - (100 - $jsonsite['hdwarn'])); ?>%;'> </td>
                                    <td title="HD usage not OK: <?php echo $jsonsite['hdwarn']; ?>% - 100%" style='background-color: #d9534f; width:<?php echo(100 - $jsonsite['hdwarn']); ?>%;'></td> 
                                </tr>
                            </table>

                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                         <div id="cpu" class="col-md-2 col-centered double-val-label">
                            <span class="<?php echo $cpuClass; ?>">CPU: </span>
                        </div>
                    </td>
                    <td id="cpubar1">
                        <div id="cpubar1"> 
                        
                            <table class='colorbar'>
                                <tr style='width: 100%;'> 
                                    <td title="CPU utilization OK: 0% - <?php echo $jsonsite['cpuok']; ?>%" style='background-color: #5cb85c; width: <?php echo $jsonsite['cpuok']; ?>%;'> </td>
                                    <td title="CPU utilization warn: <?php echo $jsonsite['cpuok']; ?>% - <?php echo $jsonsite['cpuwarn']; ?>%" style='background-color: #f0ad4e; width:<?php echo(100 - $jsonsite['cpuok'] - (100 - $jsonsite['cpuwarn'])); ?>%;'> </td>
                                    <td title="CPU utilization not OK: <?php echo $jsonsite['cpuwarn']; ?>% - 100%" style='background-color: #d9534f; width:<?php echo(100 - $jsonsite['cpuwarn']); ?>%;'></td> 
                                </tr>
                            </table>
                        
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div id="ram" class="col-md-2 col-centered double-val-label">
                            <span class="<?php echo $ramClass; ?>">RAM: </span>
                        </div>
                    </td>
                    <td id="rambar1">
                        <div id="rambar1">
                        
                            <table class='colorbar'>
                                <tr style='width: 100%;'> 
                                    <td title="RAM utilization OK: 0% - <?php echo $jsonsite['ramok']; ?>%" style='background-color: #5cb85c; width: <?php echo $jsonsite['ramok']; ?>%;'> </td>
                                    <td title="RAM utilization warn: <?php echo $jsonsite['ramok']; ?>% - <?php echo $jsonsite['ramwarn']; ?>%" style='background-color: #f0ad4e; width:<?php echo(100 - $jsonsite['ramok'] - (100 - $jsonsite['ramwarn'])); ?>%;'> </td>
                                    <td title="RAM utilization not OK: <?php echo $jsonsite['ramwarn']; ?>% - 100%" style='background-color: #d9534f; width:<?php echo(100 - $jsonsite['ramwarn']); ?>%;'></td> 
                                </tr>
                            </table>
                        
                        </div>
                    </td>
                </tr>
                
            </table>
        </div>

        <div id="footer" class="settings-footer">

            <p> <a class="footer a" href="https://github.com/monitorr/Monitorr" target="_blank" title="Monitorr Repo"> Monitorr </a> | <a class="footer a" href="https://github.com/Monitorr/Monitorr/releases" target="_blank"> <?php echo file_get_contents( "../js/version/version.txt" );?> </a> </p>

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
                    echo "No user database detected.";
                    echo "<br><br>";
                    echo "<div>";

                echo "<div id='loginmessage'>";

                    echo 'Browse to <a href="../config/_installation/_register.php">../config/_installation/_register.php</a> to create a user database and establish user credentials. ';

                echo "</div>";
            }

            //if user database is present, show log-in form:

            else {

                echo '<form method="post" action="" name="loginform">';
                    echo '<label for="login_input_username"> </label> ';
                        echo '<br>';
                    echo '<i class="fa fa-fw fa-user"></i> <input id="login_input_username" type="text" placeholder="Username" name="user_name" autofocus required /> ';

                        echo '<br>';

                    echo '<label for="login_input_password"> </label> ';
                        echo '<br>';
                    echo '<i class="fa fa-fw fa-key"></i> <input id="login_input_password" type="password"  placeholder="Password" name="user_password" required /> ';
                        echo '<br><br>';

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
                    echo "User database Dir: " .  $datadir;
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
        <title>Monitorr | Settings</title>
        <link type="text/css" href="../css/bootstrap.min.css" rel="stylesheet" />
        <link type="text/css" href="../css/main.css" rel="stylesheet">
        <link type="text/css" href="../data/css/custom.css" rel="stylesheet">
        <script type="text/javascript" src="../js/pace.js" async></script>
        <!-- <script src="../js/jquery.min.js"></script> -->

        <style type="text/css">

            body {
                color: white;
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
                padding: .375rem .75rem;
                font-size: 1rem;
                line-height: 1.5;
                color: black;
                background: rgb(200, 200, 200);
                border: 1px solid #ced4da;
                border-radius: .25rem;
                transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
            }

            input[type=password] {
                padding: .375rem .75rem;
                font-size: 1rem;
                line-height: 1.5;
                color: black;
                background: rgb(200, 200, 200);
                border: 1px solid #ced4da;
                border-radius: .25rem;
                transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
            }

        </style>

    </head>

</html>
