
<?php

        // remove all offline *.json files from log dir when changes made to "Services Configuration" settings page: 

    $files = glob("../data/logs/*.json");

    if (!empty($files)) {

        foreach($files as $file){ // iterate files in logs dir

            if(is_file($file)) {

                //delete all files in logs dir:

                if(!unlink($file)) {

                    echo "<script type='text/javascript'>";
                        echo "console.log('ERROR: Failed to remove offline log file: " . $file .  "');";
                    echo "</script>";
                }

                else {

                    echo "<script type='text/javascript'>";
                        echo "console.log('Removed offline log file: " . $file .  "');";
                    echo "</script>";
                }
            }
        }
    }

    $str2 = file_get_contents( "../data/datadir.json" );

    $json = json_decode( $str2, true);

    $datadir = $json['datadir'];

    //echo $datadir;

    $jsonpath = $datadir . 'services_settings-data.json';

    //echo $jsonpath;

        // Fail-safe to ensure blank data is NOT written to .json data file.
        // Will NOT write data to .json data file unless POST is made from "Services Configuration" settings page:

    if (isset($_POST['data']) && !empty($_POST['data'])) {

        echo "POST detected.";
            echo "<br>";
        echo  "Writing values to json settings file.";
            echo "<br>";

        file_put_contents($jsonpath, json_encode($_POST['data'], JSON_PRETTY_PRINT));
    }

    else {
        
        echo "<script type='text/javascript'>";
            echo "console.log('POST not detected. NOT writing values to json settings file.');";
        echo "</script>";

        echo "POST not detected. NOT writing values to json settings file.";
    }

?>