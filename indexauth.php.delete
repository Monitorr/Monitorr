<?php
// Initialize the session
session_start();
 
// If session variable is not set it will redirect to login page
if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
  header("location: login.php");
  exit;
}

Echo "you are now logged in.";

echo "<br>";

echo "<a href='logout.php'> Logout </a>"

?>