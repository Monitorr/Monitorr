<?php
// Define Default Location
    $default = $path . "config/_installation/default/";
    
// JSON for Default User Preferences 
    $jsonfileuser = $default . "user_preferences-data_default.json";
    $strusers = file_get_contents($jsonfileuser);
    $jsonusers = json_decode( $strusers, true);
// JSON for Default Site Settings
    $jsonfilesite = $default . "site_settings-data_default.json";
    $strsite = file_get_contents($jsonfilesite);
    $jsonsite = json_decode( $strsite, true);
// JSON for Default Services
    $jsonfileservices = $default . "services_settings-data_default.json";
    $strservices = file_get_contents($jsonfileservices);
    $jsonservices = json_decode( $strservices, true);
?>