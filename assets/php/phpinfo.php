
<link rel="stylesheet" href="../css/main.css">

    <style type="text/css">

            body::-webkit-scrollbar {
                width: .75rem;
                background-color: #252525;
            }

            body::-webkit-scrollbar-track {
                -webkit-box-shadow: inset 0 0 .25rem rgba(0, 0, 0, 0.3);
                box-shadow: inset 0 0 .25rem rgba(0, 0, 0, 0.3);
                border-radius: .75rem;
                background-color: #252525;
            }

            body::-webkit-scrollbar-thumb {
                border-radius: .75rem;
                -webkit-box-shadow: inset 0 0 .25rem rgba(0, 0, 0, .3);
                box-shadow: inset 0 0 .25rem rgba(0, 0, 0, .3);
                background-color: #8E8B8B;
            }

        #phpinfo { 
            font-size: .5rem !important; 
            cursor: default;
        }

        table { 
            width: 100% !important; 
        }

        tr { 
            font-size: 1rem !important; 
        }

        hr {
            width: 100% !important;
        }

        .v {
            width: 50% !important;
            max-width: 1vw !important;
        }

        h2 {
            font-size: 1rem !important;
        }

        a {
            color: black;
        }
        
        tbody {
            cursor: default;
        }

    </style>

<?php 

    echo "<center>";

    echo " <strong> Required Extensions: </strong> ";

    if (extension_loaded('curl')) {
        echo " <div class='extok' title='PHP cURL extension loaded OK' >";
            echo "cURL";
        echo "</div>";
    }

    else {
        echo " | <a class='extfail' href='https://github.com/Monitorr/Monitorr/wiki/01-Config:--Initial-configuration' target='_blank' title='PHP cURL extension NOT loaded'>";
            echo "cURL";
        echo "</a>";
    }

    if (extension_loaded('sqlite3')) {
        echo " | <div class='extok' title='PHP sqlite3 extension loaded OK'>";
            echo "php_sqlite3";
        echo "</div>";
    }

    else {
        echo " | <a class='extfail' href='https://github.com/Monitorr/Monitorr/wiki/01-Config:--Initial-configuration' target='_blank' title='PHP php_sqlite3 extension NOT loaded'>";
            echo "php_sqlite3";
        echo "</a>";
    }

    if (extension_loaded('pdo_sqlite')) {
        echo " | <div class='extok' title='PHP pdo_sqlite extension loaded OK'>";
            echo "pdo_sqlite";
        echo "</div>";
    }

    else {
        echo " | <a class='extfail' href='https://github.com/Monitorr/Monitorr/wiki/01-Config:--Initial-configuration' target='_blank' title='PHP pdo_sqlite extension NOT loaded'>";
            echo "pdo_sqlite";
        echo "</a>";
    }

    if (extension_loaded('zip')) {
        echo " | <div class='extok' title='PHP ZIP extension loaded OK'>";
            echo "php7-zip";
        echo "</div>";
    }

    else {
        echo " | <a class='extfail' href='https://github.com/Monitorr/Monitorr/wiki/01-Config:--Initial-configuration' target='_blank' title='php7-zip extension NOT loaded'>";
            echo "php7-zip";
        echo "</a>";
    }


        //////////// default PHP extenstions ////////////


    if (extension_loaded('date')) {
        echo " | <div class='extok' title='PHP date extension loaded OK'>";
            echo "date";
        echo "</div>";
    }

    else {
        echo " | <a class='extfail' href='https://github.com/Monitorr/Monitorr/wiki/01-Config:--Initial-configuration' target='_blank' title='PHP date extension NOT loaded'>";
            echo " date";
        echo "</a>";
    }

    if (extension_loaded('json')) {
        echo " | <div class='extok' title='PHP json extension loaded OK'>";
            echo "json";
        echo "</div>";
    }

    else {
        echo " | <a class='extfail' href='https://github.com/Monitorr/Monitorr/wiki/01-Config:--Initial-configuration' target='_blank' title='PHP json extension NOT loaded'>";
            echo " json";
        echo "</a>";
    }

    if (extension_loaded('pcre')) {
        echo " | <div class='extok' title='PHP pcre extension loaded OK'>";
            echo "pcre";
        echo "</div>";
    }

    else {
        echo " | <a class='extfail' href='https://github.com/Monitorr/Monitorr/wiki/01-Config:--Initial-configuration' target='_blank' title='PHP pcre extension NOT loaded'>";
            echo " pcre";
        echo "</a>";
    }

    if (extension_loaded('session')) {
        echo " | <div class='extok' title='PHP session extension loaded OK'>";
            echo "session";
        echo "</div>";
    }

    else {
        echo " | <a class='extfail' href='https://github.com/Monitorr/Monitorr/wiki/01-Config:--Initial-configuration' target='_blank' title='PHP session extension NOT loaded'>";
            echo " session";
        echo "</a>";
    }

    if (extension_loaded('filter')) {
        echo " | <div class='extok' title='PHP filter extension loaded OK'>";
            echo "filter";
        echo "</div>";
    }

    else {
        echo " | <a class='extfail' href='https://github.com/Monitorr/Monitorr/wiki/01-Config:--Initial-configuration' target='_blank' title='PHP filter extension NOT loaded'>";
            echo " filter";
        echo "</a>";
    }

    echo "</center>";

?>

    <div id="phpinfo">
    
        <?php phpinfo(); ?>

    </div>
