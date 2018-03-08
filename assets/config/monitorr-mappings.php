<?php
// JSON for User Preferences
    $jsonfileuser = $default . "config/_installation/default/user_preferences-data_default.json";
    $strusers = file_get_contents($jsonfileuser);
    $jsonusers = json_decode( $strusers, true);

?>
