<?php
    $fp = fopen('../data/ajax.json', 'w');
        fwrite($fp, json_encode($_POST));
    fclose($fp);

    echo '<pre>'; var_dump($_POST); echo '</pre>'
?>