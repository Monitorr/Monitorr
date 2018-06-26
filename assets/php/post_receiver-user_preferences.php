<?php

        $str2 = file_get_contents( "../data/datadir.json" );

        $json = json_decode( $str2, true);

        $datadir = $json['datadir'];

        //echo $datadir;

        $jsonpath = $datadir . 'user_preferences-data.json';

        //echo $jsonpath;

    if (isset($_POST["timezone"])) {

        echo "POST detected.";
            echo "<br>";
        echo  "timezone is set. Writing values to json settings file.";
            echo "<br>";

        $fp = fopen($jsonpath, 'w');
            fwrite($fp, json_encode($_POST));
        fclose($fp);
    }
    
    else{
        
        echo "<script type='text/javascript'>";
            echo "console.log('POST not detected. NOT writing values to json settings file.');";
        echo "</script>";

        echo "POST not detected. NOT writing values to json settings file.";
    }

?>