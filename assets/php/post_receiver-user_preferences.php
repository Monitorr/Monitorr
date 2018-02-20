<?php
    $fp = fopen('../data/user_preferences-data.json', 'w');
        fwrite($fp, json_encode($_POST));
    fclose($fp);
?>