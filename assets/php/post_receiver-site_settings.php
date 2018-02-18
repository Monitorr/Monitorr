<?php
    $fp = fopen('../data/site_settings-data.json', 'w');
        fwrite($fp, json_encode($_POST));
    fclose($fp);
?>