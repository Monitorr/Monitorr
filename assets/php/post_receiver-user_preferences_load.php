<?php

      $str = file_get_contents( "../data/datadir.json" );

      $json = json_decode( $str, true);

      $datadir = $json['datadir'];

      $jsonpath = $datadir . 'user_preferences-data.json';

      $jsonfile = file_get_contents($jsonpath);

      $jsonfile1 = json_encode( $jsonfile, true);

      echo $jsonfile1;

?>