<?php

    $str2 = file_get_contents( "../data/datadir.json" );

    $json = json_decode( $str2, true);

    $datadir = $json['datadir'];

        //echo $datadir;

    $jsonpath = $datadir . 'site_settings-data.json';

        //echo $jsonpath;

        // Fail-safe to ensure blank data is NOT written to .json data file.
        // Will NOT write data to .json data file unless POST is made from "Monitorr Settings" settings page:

    if (isset($_POST["rfsysinfo"])) {

        echo "POST detected.";
            echo "<br>";
        echo  "rfsysinfo is set. Writing values to json settings file.";
            echo "<br>";

        echo "<script type='text/javascript'>";
            echo "console.log('POST detected. Writing values to json settings file.');";
        echo "</script>";

        $fp = fopen($jsonpath, 'w');
            fwrite($fp, json_encode($_POST, JSON_PRETTY_PRINT));
        fclose($fp);
    }
    
    else{
        
        echo "<script type='text/javascript'>";
            echo "console.log('POST not detected. NOT writing values to json settings file.');";
        echo "</script>";

        echo "POST not detected. NOT writing values to json settings file.";
    }

?>