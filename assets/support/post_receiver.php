<?php
    $fp = fopen('datadir.txt', 'w');
        fwrite($fp, json_encode($_POST));
    fclose($fp);

?>