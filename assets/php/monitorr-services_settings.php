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
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="manifest" href="webmanifest.json">
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
        <link rel="apple-touch-icon" href="favicon.ico">
        <link type="text/css" href="../css/bootstrap.min.css" rel="stylesheet">
        <link type="text/css" href="../css/alpaca.min.css" rel="stylesheet">
        <!-- <link type="text/css" href="../css/main.css" rel="stylesheet"> -->

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
                font-size: 1rem !important;
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

            .navbar-brand {
                cursor: default;
            }

            img {
                width: 7rem !important;
                color: white;
            }

            .form-control {
                width: inherit !important;
            }

            .alpaca-message-invalidPattern {
                margin-left: -2rem;
                margin-top: -5rem;
            }

            .alpaca-form-buttons-container {
                position: fixed;
                margin-left: 1rem;
                bottom: 3rem;
            }

            input[type=checkbox], input[type=radio] {
                cursor: pointer;
            }

        </style>

        <?php $datafile = '../data/datadir.json'; ?>
        <?php include_once ('../config/monitorr-data.php')?>

        <title>
            <?php
                $title = $jsonusers['sitetitle'];
                echo $title . PHP_EOL;
            ?>
            | Service Config
        </title>

    </head>

    <body>

        <script>
            document.body.className += ' fade-out';
            $(function() {
                $('body').removeClass('fade-out');
            });
        </script>

        <div id="centertext">
            <div class="navbar-brand">
                Services Configuration
            </div>
        </div>

        <p id="response"></p>

        <div id="serviceform">

            <div id="servicesettings"></div>

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
                        Alpaca.registerConnectorClass("custom", CustomConnector);
                        $("#servicesettings").alpaca({
                            "connector": "custom",
                            "dataSource": "./post_receiver-services_load.php",
                            "schemaSource": "../config/services-schema.json?a=1",
                            "view": {
                                "fields": {
                                    "//checkurl": {
                                        "templates": {
                                            "control": "../css/./templates-checkurl-control.html"
                                        }
                                    },
                                    "//linkurl": {
                                        "templates": {
                                            "control": "../css/./templates-linkurl-control.html"
                                        }
                                    },
                                    "//serviceTitle": {
                                        "templates": {
                                            "control": "../css/./templates-services_title.html"
                                        },
                                        "bindings": {
                                            "serviceTitle": "#title_input"
                                        }
                                    },
                                    "//enabled": {
                                        "templates": {
                                            "control": "../css/./templates-services_enabled.html"
                                        },
                                        "bindings": {
                                            "enabled": "#enabled_option"
                                        }
                                    }
                                }
                                // "parent": "bootstrap-edit-horizontal",
                                // "layout": {
                                //    "template": './two-column-layout-template-services.html',
                                //     "bindings": {
                                //         "serviceTitle": "#leftservice",
                                //         "image": "#leftservice",
                                //         "checkurl": "#rightservice",
                                //         "linkurl": "#rightservice",
                                //         "type": "#rightservice"
                                //     }
                                // }
                            },
                            "options": {
                                "toolbarSticky": true,
                                "collapsible": true,
                                "actionbar": {
                                    "showLabels": true,
                                    "actions": [{
                                        "label": "Add Service",
                                        "action": "add",
                                        "iconClass": "fa fa-plus",
                                    }, {
                                        "label": "Remove Service",
                                        "action": "remove",
                                        "iconClass": "fa fa-minus"
                                    }, {
                                        "label": "Move UP",
                                        "action": "up",
                                        "iconClass": "fa fa-arrow-up",
                                        "enabled": true
                                    }, {
                                        "label": "Move Down",
                                        "action": "down",
                                        "iconClass": "fa fa-arrow-down",
                                        "enabled": true
                                    }, {
                                        "label": "Clear",
                                        "action": "clear",
                                        "iconClass": "fa fa-trash",
                                        "click": function(key, action, itemIndex) {
                                            var item = this.children[itemIndex];
                                            item.setValue("");
                                        }
                                    }, {
                                        "label": "Images",
                                        "action": "",
                                        "iconClass": "fa fa-image",
                                        "click": function() {

                                            var modal = document.getElementById('myModal3');
                                            var span = document.getElementsByClassName("closeimg")[0];
                                            modal.style.display = "block";

                                            span.onclick = function() {
                                                modal.style.display = "none";
                                            }

                                            window.onclick = function(event) {
                                                if (event.target == modal) {
                                                    modal.style.display = "none";
                                                }
                                            }
                                        }
                                    }
                                    ]
                                },
                            "items": {
                                    "fields": {
                                        "serviceTitle": {
                                            "type": "text",
                                            "validate": true,
                                            "showMessages": true,
                                            "disabled": false,
                                            "hidden": false,
                                            "label": "Service Title:",
                                            //"helpers": ["Name of Service"],
                                            //"helper": "Name of Service",
                                            "hideInitValidationError": false,
                                            "focus": false,
                                            "optionLabels": [],
                                            "name": "serviceTitle",
                                            "size": 20,
                                            "placeholder": "Service Name",
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
                                        "enabled": {
                                            "type": "select",
                                            "validate": false, // ** CHANGE ME ** change to TRUE to allow for user config propegation//
                                            "showMessages": true,
                                            "disabled": false,
                                            "hidden": false,
                                            "label": "Enabled:",
                                            "helpers": ["When disabled, service will NOT be checked or displayed in the UI."],
                                            "helper": "When disabled, service will NOT be checked or displayed in the UI",
                                            "hideInitValidationError": false,
                                            "focus": false,
                                            //"optionLabels": ["True", "False"],
                                            "name": "enabled",
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
                                        "image": {
                                            "type": "image",
                                            "validate": true,
                                            "showMessages": true,
                                            "disabled": false,
                                            "hidden": false,
                                            "label": "Service Image:",
                                            //"helpers": ["Icon/image representation of service"],
                                            "helper": "Icon/image representation of service. <br> Location of image must be present in the /assets/img directory.",
                                            "hideInitValidationError": false,
                                            "focus": false,
                                            "optionLabels": [],
                                            "size": 20,
                                            "name": "image",
                                            "styled": true,
                                            "placeholder": "../img/monitorr.png",
                                            "typeahead": {},
                                            "allowOptionalEmpty": false,
                                            "data": {},
                                            "autocomplete": false,
                                            "disallowEmptySpaces": true,
                                            "disallowOnlyEmptySpaces": true,
                                            "fields": {},
                                            "renderButtons": true,
                                            "attributes": {},
                                            "onFieldChange": function(e) {

                                                // Window/modal will appear with image when user inputs path into "service Image" text field and clicks out of field:"
                                                var value = this.getValue();
                                                if (value) {
                                                    var img = $("<img src='../img/" + value + "' style='width:7rem' alt=' image not found'>");
                                                    $("#mymodal2").append(img);
                                                }

                                                var modal = document.getElementById('myModal');
                                                var span = document.getElementsByClassName("modal")[0];
                                                modal.style.display = "block";

                                                span.onclick = function() {
                                                    modal.style.display = "none";
                                                    $('#mymodal2').empty();
                                                }

                                                window.onclick = function(event) {
                                                    if (event.target == modal) {
                                                        modal.style.display = "none";
                                                        $('#mymodal2').empty();
                                                    }
                                                }

                                                $('.alpaca-form-button-submit').addClass('buttonchange');
                                            }
                                        },
                                        "type": {
                                            "type": "select",
                                            "validate": true,
                                            //"optionLabels": [" Standard", " Ping Only"],
                                            "showMessages": true,
                                            "disabled": false,
                                            "hidden": false,
                                            "label": "Check Type:",
                                            "helpers": ["Standard: Services that serve a webpage. <br> Ping: Services that only listen on defined port."],
                                            "hideInitValidationError": false,
                                            "focus": false,
                                            "name": "checktype",
                                            //"placeholder": " Standard",
                                            "typeahead": {},
                                            "styled": true,
                                            "allowOptionalEmpty": false,
                                            "hideNone": true,
                                            "data": {},
                                            "autocomplete": false,
                                            "disallowEmptySpaces": true,
                                            "disallowOnlyEmptySpaces": false,
                                            "removeDefaultNone": true,
                                            "fields": {},
                                            "renderButtons": true,
                                            "attributes": {},
                                            "events": {
                                                "change": function() {
                                                    $('.alpaca-form-button-submit').addClass('buttonchange');
                                                }
                                            }
                                        },
                                        "checkurl": {
                                            "type": "url",
                                            "validate": true,
                                            "allowIntranet": true,
                                            "showMessages": true,
                                            "disabled": false,
                                            "hidden": false,
                                            "label": "Check URL:",
                                            "size": 30,
                                            //"helpers": ["URL to check status"],
                                            "helper": "URL to check service status. (Port is required!)",
                                            "hideInitValidationError": false,
                                            "focus": false,
                                            "optionLabels": [],
                                            "name": "checkurl",
                                            "placeholder": "http://localhost:80",
                                            "typeahead": {},
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
                                        "linkurl": {
                                            "dependencies": {
                                                "type": [" Standard"]
                                            },
                                            "type": "url",
                                            "validate": false,
                                            "allowIntranet": true,
                                            "showMessages": true,
                                            "disabled": false,
                                            "hidden": false,
                                            "label": "Link URL:",
                                            "size": 30,
                                            //"helpers": ["URL that will be linked to service"],
                                            "helper": "URL that will be linked to service from the UI. ('Link URL' field value is not applied if using 'ping only' option)",
                                            "hideInitValidationError": false,
                                            "focus": false,
                                            "optionLabels": [],
                                            "name": "linkurl",
                                            "placeholder": "http://localhost:80",
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
                                        }
                                    },
                            },
                            "form": {
                                    "attributes": {
                                        "action": "post_receiver-services.php",
                                        "method": "post",
                                        "contentType": "application/json"
                                        //"enctype": "json"
                                    },
                                    "buttons": {
                                        "submit": {
                                            // "type": 'button',
                                            "click": function formsubmit() {
                                                var data = $('#servicesettings').alpaca().getValue();
                                                $.post('post_receiver-services.php', {
                                                        data,
                                                        success: function(data){
                                                            alert("Settings saved! Applying changes...");
                                                                // Refresh form after submit:
                                                            setTimeout(location.reload.bind(location), 1000)
                                                        },
                                                        error: function(errorThrown){
                                                            console.log(errorThrown);
                                                        }
                                                    },
                                                )
                                                $('.alpaca-form-button-submit').removeClass('buttonchange');
                                            }
                                        },
                                        "reset":{
                                            "label": "Clear Values"
                                        }
                                    }
                                }
                            }
                        });
                    });
                </script>
        </div>

            <!-- Modal pop-up for "Service Image" input field: -->

        <div id="myModal" class="modal">

            <p class="modaltext">Service Image Preview:</p>
                <!-- Modal content -->
            <div id="mymodal2" class="modal-content"></div>
            <span class="close"  aria-hidden="true" title="close preview">&times;</span>

        </div>

            <!-- Modal pop-up for images directory display: -->

        <div id="myModal3" >

            <span class="closeimg"  aria-hidden="true" title="close images">&times;</span>

            <p class="modaltext">Images:</p>
                <?php $imgpath = '../img/'; ?>
            <p class="modalimgpath"> <?php echo realpath($imgpath); ?> </p>

                <!-- Modal content -->
            <div id="mymodal4"> 
            
                <?php

                    $dirname = '../img/';
                    $images = glob($dirname.'*.*');

                    $count = 0;

                    foreach ($images as $image) {

                        echo '<div id="imgthumb" class="imgthumb">';

                            echo '<button id="imgbtn" onclick="copyFunction(' . $count . ')">';
                                echo '<center>';
                                    echo '<img src="'.$image.'" style="width:7rem" title="click to copy"/>';
                                echo '</center>';
                            echo '</button>';

                            echo '<div id="imgpath">';
                                echo '<input type="text" value="'.$image.'"  id="'.$count.'" name="imginput" readonly>';

                            echo '</div>';
                        echo '</div>';

                        ++$count;
                    }
                ?>
            
            </div>

        </div>

                 <!-- Click-to-copy function -->

            <script>
                function copyFunction() {
                    var thumbs = document.querySelectorAll('.imgthumb');
                    thumbs.forEach( function ( thumb ) {
                        var button = thumb.querySelector('button');
                        var input = thumb.querySelector('input');
                        button.addEventListener('click', function () {
                            input.select();
                            document.execCommand("Copy");
                        })
                    })
                }
            </script>

                <!-- scroll to top   -->

        <button onclick="topFunction()" id="myBtn" title="Go to top"></button>

            <script>

                // When the user scrolls down 20px from the top of the document, show the button
                window.onscroll = function() {scrollFunction()};

                function scrollFunction() {
                    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                        document.getElementById("myBtn").style.display = "block";
                    } else {
                        document.getElementById("myBtn").style.display = "none";
                    }
                }

                // When the user clicks on the button, scroll to the top of the document
                function topFunction() {
                    document.body.scrollTop = 0;
                    document.documentElement.scrollTop = 0;
                }

            </script>

        <div id="footer">

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

            else {


                echo '<form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '" name="loginform">';
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
        <title>Monitorr | Login</title>
        <link type="text/css" href="../css/bootstrap.min.css" rel="stylesheet" />
        <link type="text/css" href="../css/main.css" rel="stylesheet">
        <script type="text/javascript" src="../js/pace.js" async></script>

        <!-- <script src="../js/jquery.min.js"></script> -->

        <style type="text/css">

            body {
                color: white;
            }

            .wrapper {
                width: 30rem;
                margin-top: 10%;
                margin-left: auto;
                margin-right: auto;
                padding: 1rem;
            }

            .navbar-brand {
                cursor: default;
            }

        </style>


    </head>

</html>
