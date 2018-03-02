<?php


    $fp = fopen('datadir.txt', 'w');
        fwrite($fp, json_encode($_POST));
    fclose($fp);


    $str = file_get_contents('datadir.txt');

    $datadir = json_decode( $str, true);


    // Desired folder structure

    // $structure = '../../../../../depth1'; root path +1
    //$structure = './depth1';

    $structure = $datadir['datadir'];

    // To create the nested structure, the $recursive parameter 
    // to mkdir() must be specified.

    if (!mkdir($structure, 0777, true)) {
        die('Failed to create folders...');
        echo "Failed to create $structure";
    }

    else  {


        $vnum_loc = "$structure";
        echo realpath($vnum_loc), PHP_EOL;

        echo "created successfully";
    }

    echo "<br> <br>";



    // ...

    exit;


?>