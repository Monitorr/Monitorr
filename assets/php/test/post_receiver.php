<?php
    $fp = fopen('../data/icecream-data.json', 'w');
        fwrite($fp, json_encode($_POST));
    fclose($fp);

?>