<?php

    $datafile = '../../data/datadir.json'; 
    $str = file_get_contents($datafile);
    $json = json_decode( $str, true);
    $datadir = $json['datadir'];

    
    echo "cwd: " . getcwd();
        echo "<br>";
    echo "datafile: " . $datafile;
        echo "<br>";
    echo "datafile real path: " . realpath($datafile), PHP_EOL;
        echo "<br>";
    echo "datadir: " . $datadir;
        echo "<br>";
    echo "datadir real path: " . realpath($datadir), PHP_EOL;
        echo "<br>";

    echo "datadir contents START: ";
        echo "<br>";

        $dir = $datadir;
        //$files1 = scandir($dir);
        $files2 = scandir($dir, 1);

        //print_r($files1);
        print_r($files2) . PHP_EOL;

            echo "<br>";

    echo "datadir contents END. ";


    ?>