<?php
    $fp = fopen('../data/timezone-data.json', 'w');
        fwrite($fp, json_encode($_POST));
    fclose($fp);
?>