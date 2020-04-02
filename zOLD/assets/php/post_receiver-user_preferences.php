<?php

        $str2 = file_get_contents( "../data/datadir.json" );

        $json = json_decode( $str2, true);

        $datadir = $json['datadir'];

        //echo $datadir;

        $jsonpath = $datadir . 'user_preferences-data.json';

        //echo $jsonpath;

        // Fail-safe to ensure blank data is NOT written to .json data file.
        // Will NOT write data to .json data file unless POST is made from "User Preferences" settings page:

    if (isset($_POST["timezone"])) {

        echo "POST detected.";
            echo "<br>";
        echo  "timezone is set. Writing values to json settings file.";
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