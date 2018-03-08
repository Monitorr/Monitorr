<?php
// JSON for User Preferences
    $jsonfileuser = $default . "config/_installation/default/user_preferences-data_default.json";
    $strusers = file_get_contents($jsonfileuser);
    $jsonusers = json_decode( $strusers, true);
// JSON for Site Settings
    $jsonfilesite = $default . "config/_installation/default/site_settings-data.json";
    $strsite = file_get_contents($jsonfilesite);
    $jsonsite = json_decode( $strsite, true);
// JSON for Services
    $jsonfileservices = $default . "config/_installation/default/services_settings-data.json";
    $strservices = file_get_contents($jsonfileservices);
    $jsonservices = json_decode( $strservices, true);
?>
