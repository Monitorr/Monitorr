<?php
// Include config file
require_once 'assets/data/auth_config.php';
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = 'Please enter username.';
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST['password']))){
        $password_err = 'Please enter your password.';
    } else{
        $password = trim($_POST['password']);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            /* Password is correct, so start a new session and
                            save the username to the session */
                            session_start();
                            $_SESSION['username'] = $username;      
                            header("location: assets/php/phpinfo.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = 'The password you entered was not valid.';
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = 'No account found with that username.';
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <title>Monitorr | Login</title>
    <!-- <link rel="stylesheet" href="assets/css/bootstrap.css"> -->
    <link type="text/css" href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <link type="text/css" href="assets/css/main.css" rel="stylesheet">

    <style type="text/css">

        body { 
            /* font: 14px sans-serif; */
            color: white;
        }

        :root {
            font-size: 16px !important;
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
    <div class="wrapper">


         <div class="navbar-brand">
            <h2>Monitorr | Login</h2>
        </div>
        <br> <br>


        <!-- <p>Please fill in your credentials to login.</p> -->

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">

                <label>Username</label>

                <input type="text" name="username"class="form-control" value="<?php echo $username; ?>">

                <span class="help-block"><?php echo $username_err; ?></span>

            </div>

            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>

            <div class="form-group">

                <input type="submit" class="btn btn-primary" value="Login">

            </div>
            <!-- <p>Don't have an account? <a href="register.php">Sign up now</a>.</p> -->

        </form>

        un: guest  pw:  monitorr
        <br> <br>
        <!-- TO DO: add note about how to reset UN and PW and link to wiki if FAIL x times. -->

    </div>    
</body>
</html>