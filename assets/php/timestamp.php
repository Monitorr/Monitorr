<?php 

    $datafile = '../data/datadir.json';
    $str = file_get_contents($datafile);
    $json = json_decode( $str, true);
    $datadir = $json['datadir'];
    $jsonfileuserdata = $datadir . 'user_preferences-data.json';

    if(!is_file($jsonfileuserdata)){

        $path = "../";
        include_once ('../config/monitorr-data-default.php');
        $timezone = $jsonusers['timezone'];
        $timestandard = (int) ($jsonusers['timestandard'] === "True" ? true:false);
    
    } 

    else {

        $datafile = '../data/datadir.json';
        include_once ('../config/monitorr-data.php');
        $timezone = $jsonusers['timezone'];
        $timestandard = (int) ($jsonusers['timestandard'] === "True" ? true:false);

    }

    date_default_timezone_set($timezone);

    $dt = new DateTime("now", new DateTimeZone("$timezone"));

    $rftime = $jsonsite['rftime'];
    
        // 24-hour time format:

    if ($timestandard=='False'){
        $dateTime = new DateTime();
        $dateTime->setTimeZone(new DateTimeZone($timezone));
        $timezone_suffix = $dateTime->format('T');
        $serverTime = $dt->format("D d M Y H:i:s");
    }

        // 12-hour time format:

    else {

        $dateTime = new DateTime();
        $dateTime->setTimeZone(new DateTimeZone($timezone));
        $timezone_suffix = '';
        $serverTime = $dt->format("D d M Y g:i:s A");
    }
    
    $response = array(
        'serverTime' => $serverTime,
        'timestandard' => $timestandard,
        'timezoneSuffix' => $timezone_suffix,
        'rftime' => $rftime
    );

    echo json_encode($response);

?>