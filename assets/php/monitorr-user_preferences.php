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
        <script type="text/javascript" src="../js/handlebars.js"></script>
        <script type="text/javascript" src="../js/bootstrap.min.js"></script>
        <script type="text/javascript" src="../js/alpaca.min.js"></script>
        <script type="text/javascript" src="../js/ace.js"></script>
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.9/ace.js"></script> -->
       
            <style>

                body {
                    margin: 2vw !important;
                    overflow-y: auto;
                    overflow-x: hidden;
                    color: white !important;
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

                label {
                    width: 100% !important;
                    max-width: 100% !important;
                }
                
                input[type=checkbox], input[type=radio] {
                    cursor: pointer;
                }

                .alpaca-message-invalidValueOfEnum {
                    margin-top: 1rem !important;
                }


            </style>

        <?php $datafile = '../data/datadir.json'; ?>
        <?php include_once ('../config/monitorr-data.php')?>

        <title>
            <?php
                $title = $jsonusers['sitetitle'];
                echo $title . PHP_EOL;
            ?>
            | User Preferences
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
                User Preferences
            </div>
        </div>

        <div id="preferenceform">

            <div id="preferencesettings"></div>

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
                            url: './post_receiver-user_preferences_load.php',
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

                        $("#preferencesettings").alpaca({
                            "connector": "custom",
                            "dataSource": "./post_receiver-user_preferences_load.php",
                            "schemaSource": "../config/user_preferences-schema.json?a=1",
                            "view": {
                                "parent": "bootstrap-edit-horizontal",
                                "layout": {
                                    "template": '../css/./two-column-layout-template-user-preferences.html',
                                    "bindings": {
                                        "sitetitle": "leftcolumnuser",
                                        "siteurl": "leftcolumnuser",
                                        "updateBranch": "leftcolumnuser",
                                        "registration": "rightcolumnuser",
                                        // "language": "rightcolumn",
                                        "timezone": "rightcolumnuser",
                                        "timestandard": "rightcolumnuser"
                                    }
                                },
                                "fields": {
                                    "/sitetitle": {
                                        "templates": {
                                            "control": "../css/forms/./templates-user-preferences_title.html"
                                        },
                                        "bindings": {
                                            "sitetitle": "#site_title_input"
                                        }
                                    },
                                    "/siteurl": {
                                        "templates": {
                                            "control": "../css/forms/./templates-user-preferences_url.html"
                                        },
                                        "bindings": {
                                            "siteurl": "#site_url_input"
                                        }
                                    },
                                    "/updateBranch": {
                                        "templates": {
                                            "control": "../css/forms/./templates-user-preferences_updatebranch.html"
                                        },
                                        "bindings": {
                                            "updateBranch": "#updatebranch"
                                        }
                                    },
                                    "/registration": {
                                        "templates": {
                                            "control": "../css/forms/./templates-user-preferences_registration.html"
                                        },
                                        "bindings": {
                                            "registration": "#registration"
                                        }
                                    },
                                    "/timezone": {
                                        "templates": {
                                            "control": "../css/forms/./templates-user-preferences_timezone.html"
                                        },
                                        "bindings": {
                                            "timzone": "#timezone"
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
                                    "sitetitle": {
                                        "type": "text",
                                        "validate": false,
                                        "showMessages": false,
                                        "disabled": false,
                                        "hidden": false,
                                        "label": "Site Title:",
                                        "constrainMaxLength": true,
                                        "showMaxLengthIndicator": true,
                                        "helpers": ["Text that is displayed in the top header."],
                                        "hideInitValidationError": false,
                                        "focus": false,
                                        "optionLabels": [],
                                        "name": "sitetitle",
                                        "placeholder": "Monitorr",
                                        "typeahead": {},
                                        "allowOptionalEmpty": true,
                                        "data": {},
                                        "autocomplete": false,
                                        "disallowEmptySpaces": false,
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
                                    "siteurl": {
                                        "type": "url",
                                        "validate": true,
                                        "showMessages": true,
                                        "disabled": false,
                                        "hidden": false,
                                        "label": "Site URL:",
                                        "size": 30,
                                        "helpers": ["URL of the Monitorr UI."],
                                        "hideInitValidationError": false,
                                        "focus": false,
                                        "optionLabels": [],
                                        "name": "siteurl",
                                        "placeholder": "http://localhost/monitorr",
                                        "typeahead": {},
                                        "allowOptionalEmpty": false,
                                        "data": {},
                                        "autocomplete": "false",
                                        "disallowEmptySpaces": true,
                                        "disallowOnlyEmptySpaces": false,
                                        "allowIntranet": true,
                                        "fields": {},
                                        "events": {
                                            "change": function() {
                                                $('.alpaca-form-button-submit').addClass('buttonchange');
                                            }
                                        }
                                    },
                                    "updateBranch": {
                                        "type": "radio",
                                        "validate": true,
                                        "showMessages": true,
                                        "disabled": false,
                                        "hidden": false,
                                        "label": "Update Branch:",
                                        "helpers": ["Monitorr repo branch to use when updating."],
                                        "hideInitValidationError": false,
                                        "focus": false,
                                        "optionLabels": [" Master", " Develop"],
                                        "name": "updateBranch",
                                        "typeahead": {},
                                        "allowOptionalEmpty": false,
                                        "data": {},
                                        "autocomplete": "false",
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
                                    "registration": {
                                        "type": "select",
                                        "validate": true, // ** CHANGE ME ** change to TRUE to allow for user config propegation//
                                        "showMessages": true,
                                        "disabled": false,
                                        "hidden": false,
                                        "label": "Registration:",
                                        "hideInitValidationError": false,
                                        "focus": false,
                                        "name": "registration",
                                        "typeahead": {},
                                        "allowOptionalEmpty": false,
                                        "data": {},
                                        "autocomplete": false,
                                        "disallowEmptySpaces": true,
                                        "disallowOnlyEmptySpaces": false,
                                        "removeDefaultNone": true,
                                        "fields": {},
                                        "events": {
                                            "ready": function(callback) {
                                                var value = this.getValue();
                                                if (value == "Enable") {
                                                    $('.registrationwarning').removeClass('registrationwarningchange');
                                                } else {
                                                    $('.registrationwarning').addClass('registrationwarningchange');
                                                }
                                            },
                                            "change": function(callback) {
                                                var value = this.getValue();
                                                $('.alpaca-form-button-submit').addClass('buttonchange');
                                                if (value == "Enable") {
                                                    $('.registrationwarning').removeClass('registrationwarningchange');
                                                } else {
                                                    $('.registrationwarning').addClass('registrationwarningchange');
                                                }
                                            }
                                        }
                                    },
                                    "timezone": {
                                        "type": "select",
                                        "validate": true,
                                        "showMessages": true,
                                        "disabled": false,
                                        "hidden": false,
                                        "label": "Timezone:",
                                        "helpers": ["Timezone to use for UI display."],
                                        "hideInitValidationError": false,
                                        "focus": false,
                                        "optionLabels": [
                                            "(GMT-11:00) Samoa",
                                            "(GMT-10:00) Hawaii",
                                            "(GMT-09:00) Alaska",
                                            "(GMT-08:00) Pacific Time",
                                            "(GMT-07:00) Mountain Time",
                                            "(GMT-07:00) Chihuahua",
                                            "(GMT-07:00) Mazatlan",
                                            "(GMT-06:00) Mexico City",
                                            "(GMT-06:00) Monterrey",
                                            "(GMT-06:00) Saskatchewan",
                                            "(GMT-06:00) Central Time",
                                            "(GMT-05:00) Eastern Time",
                                            "(GMT-05:00) Indiana (East)",
                                            "(GMT-05:00) Bogota",
                                            "(GMT-05:00) Lima",
                                            "(GMT-04:30) Caracas",
                                            "(GMT-04:00) Atlantic Time (Canada)",
                                            "(GMT-04:00) La Paz",
                                            "(GMT-04:00) Santiago",
                                            "(GMT-03:30) Newfoundland",
                                            "(GMT-03:00) Buenos Aires",
                                            "(GMT-03:00) Greenland",
                                            "(GMT-02:00) Stanley",
                                            "(GMT-01:00) Azores",
                                            "(GMT-01:00) Cape Verde Is.",
                                            "(GMT) Casablanca",
                                            "(GMT) Dublin",
                                            "(GMT) Lisbon",
                                            "(GMT) London",
                                            "(GMT) Monrovia",
                                            "(GMT+01:00) Amsterdam",
                                            "(GMT+01:00) Belgrade",
                                            "(GMT+01:00) Berlin",
                                            "(GMT+01:00) Bratislava",
                                            "(GMT+01:00) Brussels",
                                            "(GMT+01:00) Budapest",
                                            "(GMT+01:00) Copenhagen",
                                            "(GMT+01:00) Ljubljana",
                                            "(GMT+01:00) Madrid",
                                            "(GMT+01:00) Paris",
                                            "(GMT+01:00) Prague",
                                            "(GMT+01:00) Rome",
                                            "(GMT+01:00) Sarajevo",
                                            "(GMT+01:00) Skopje",
                                            "(GMT+01:00) Stockholm",
                                            "(GMT+01:00) Vienna",
                                            "(GMT+01:00) Warsaw",
                                            "(GMT+01:00) Zagreb",
                                            "(GMT+02:00) Athens",
                                            "(GMT+02:00) Bucharest",
                                            "(GMT+02:00) Africa/Cairo",
                                            "(GMT+02:00) Harare",
                                            "(GMT+02:00) Helsinki",
                                            "(GMT+02:00) Istanbul",
                                            "(GMT+02:00) Jerusalem",
                                            "(GMT+02:00) Kyiv",
                                            "(GMT+02:00) Minsk",
                                            "(GMT+02:00) Riga",
                                            "(GMT+02:00) Sofia",
                                            "(GMT+02:00) Tallinn",
                                            "(GMT+02:00) Vilniu",
                                            "(GMT+03:00) Baghdad",
                                            "(GMT+03:00) Kuwait",
                                            "(GMT+03:00) Nairobi",
                                            "(GMT+03:00) Riyadh",
                                            "(GMT+03:00) Moscow",
                                            "(GMT+03:30) Tehran",
                                            "(GMT+04:00) Baku",
                                            "(GMT+04:00) Volgograd",
                                            "(GMT+04:00) Muscat",
                                            "(GMT+04:00) Tbilisi",
                                            "(GMT+04:00) Yerevan",
                                            "(GMT+04:30) Kabul",
                                            "(GMT+05:00) Karachi",
                                            "(GMT+05:00) Tashkent",
                                            "(GMT+05:30) Kolkata",
                                            "(GMT+05:45) Kathmandu",
                                            "(GMT+06:00) Ekaterinburg",
                                            "(GMT+06:00) Almaty",
                                            "(GMT+06:00) Dhaka",
                                            "(GMT+07:00) Novosibirsk",
                                            "(GMT+07:00) Bangkok",
                                            "(GMT+07:00) Jakarta",
                                            "(GMT+08:00) Krasnoyarsk",
                                            "(GMT+08:00) Chongqing",
                                            "(GMT+08:00) Hong Kong",
                                            "(GMT+08:00) Kuala Lumpur",
                                            "(GMT+08:00) Perth",
                                            "(GMT+08:00) Singapore",
                                            "(GMT+08:00) Taipei",
                                            "(GMT+08:00) Ulaan Bataar",
                                            "(GMT+08:00) Urumqi",
                                            "(GMT+09:00) Irkutsk",
                                            "(GMT+09:00) Seoul",
                                            "(GMT+09:00) Tokyo",
                                            "(GMT+09:30) Adelaide",
                                            "(GMT+09:30) Darwin",
                                            "(GMT+10:00) Yakutsk",
                                            "(GMT+10:00) Brisbane",
                                            "(GMT+10:00) Canberra",
                                            "(GMT+10:00) Guam",
                                            "(GMT+10:00) Hobart",
                                            "(GMT+10:00) Melbourne",
                                            "(GMT+10:00) Port Moresby",
                                            "(GMT+10:00) Sydney",
                                            "(GMT+11:00) Vladivostok",
                                            "(GMT+12:00) Magadan",
                                            "(GMT+12:00) Auckland",
                                            "(GMT+12:00) Fiji"
                                        ],
                                        "name": "timezone",
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
                                    "timestandard": {
                                        "type": "radio",
                                        "validate": true,
                                        "showMessages": true,
                                        "disabled": false,
                                        "hidden": false,
                                        "label": "Time Standard:",
                                        //"helpers": ["24h time display (FALSE)  / 12h time display (TRUE)."],
                                        "hideInitValidationError": false,
                                        "focus": false,
                                        "optionLabels": [" True (12h time)", " False (24h time)"],
                                        "name": "timestandard",
                                        "typeahead": {},
                                        "allowOptionalEmpty": false,
                                        "data": {},
                                        "autocomplete": "false",
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
                                    "language": {
                                        "type": "text",
                                        "validate": false,
                                        "showMessages": false,
                                        "disabled": false,
                                        "hidden": false,
                                        "label": "Language:",
                                        "helpers": ["Beta"], // BETA - CHANGE ME //
                                        "hideInitValidationError": false,
                                        "focus": false,
                                        "optionLabels": [],
                                        "name": "language",
                                        "placeholder": "EN",
                                        "typeahead": {},
                                        "allowOptionalEmpty": false,
                                        "data": {},
                                        "autocomplete": false,
                                        "disallowEmptySpaces": false,
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
                                },
                                "form": {
                                    // "attributes": {
                                    //     "action": "post_receiver-user_preferences.php",
                                    //     "method": "post",
                                    // },
                                    "buttons": {
                                        "submit": {
                                            "type": "button",
                                            "label": "Submit",
                                            "name": "submit",
                                            "value": "submit",
                                            click: function(){
                                                var data = $('#preferencesettings').alpaca().getValue();
                                                $.post({
                                                    url: 'post_receiver-user_preferences.php',
                                                    data: $('#preferencesettings').alpaca().getValue(),
                                                    success: function(data) {
                                                        console.log("Settings saved! Applying changes...");
                                                        alert("Settings saved! Applying changes...");
                                                        setTimeout(function () { window.top.location = "../../settings.php" }, 3000);
                                                    },
                                                    error: function(errorThrown){
                                                        console.log(errorThrown);
                                                        alert("Error submitting data.");
                                                    }
                                                });

                                                $.post('post_receiver_custom_css.php', { 
                                                    css: cssEditor.getSession().getValue()
                                                    }, 
                                                    function(data) {}
                                                );
                                                $('.alpaca-form-button-submit').removeClass('buttonchange');
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
                                cssEditor = ace.edit("customCSSEditor");
                                cssEditor.getSession().setMode("ace/mode/css");
                                cssEditor.setTheme("ace/theme/idle_fingers");

                                //load the custom css file into the form
                                $.when($.get("../data/css/custom.css"))
                                .done(function(response) {
                                    cssEditor.getSession().setValue(response);
                                    console.log('Loaded custom CSS form.');
                                });
                            },
                        });
                    });
                </script>

        </div>

        <div id="footer" class="settings-footer">

            <a class="footer a" href="https://github.com/monitorr/Monitorr" target="_blank" title="Monitorr Repo"> Monitorr </a> | <a class="footer a" href="https://github.com/Monitorr/Monitorr/releases" target="_blank"> <?php echo file_get_contents( "../js/version/version.txt" );?> </a>

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
                /* font: 14px sans-serif; */
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
