<?php

    $json = json_encode( $_POST, true);
    foreach($json as $key => $value);

    //foreach($json as $key => $value);
        
    //$_POST = json_encode($json);

    $fp = fopen('../data/services_settings-data.json', 'w');
        fwrite( $fp, $json);
    fclose($fp);

?>