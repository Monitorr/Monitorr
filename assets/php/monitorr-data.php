<?php
// Data for Monitorr
// Data Directory Definition file
    $datafile = '../config/datadir.json';
// Data Directory
    $str = file_get_contents($datafile);
    $json = json_decode( $str, true);
    $datadir = $json['datadir'];
// Login Database
    $dbfile = $datadir . 'users.db';
    $db_sqlite_path = $dbfile;
// JSON for User Preferences
    $jsonfileuser = $datadir . 'user_preferences-data.json';
    $strusers = file_get_contents($jsonfileuser);
    $jsonusers = json_decode( $strusers, true);
// JSON for Site Settings
    $jsonfilesite = $datadir . 'site_settings-data.json';
    $strsite = file_get_contents($jsonfilesite);
    $jsonsite = json_decode( $strsite, true);
// JSON for Services
    $jsonfileservices = $datadir . 'services_settings-data.json';
    $strservices = file_get_contents($jsonfileservices);
    $jsonservices = json_decode( $strservices, true);
?>
